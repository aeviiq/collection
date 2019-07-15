<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \ArrayIterator|object[] getIterator
 * @method object|null first
 * @method object|null last
 */
abstract class AbstractObjectCollection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        array $elements = [],
        string $iteratorClass = \ArrayIterator::class
    ) {
        // We hardcode the flags here to prevent bugs that could occur when reflection is used on these collections.
        // For a more detailed explanation see https://github.com/aeviiq/collection/issues/19
        // The flags can still be changed using the setFlags() method, although this is not recommended.
        parent::__construct($elements, \ArrayObject::ARRAY_AS_PROPS, $iteratorClass);
    }

    /**
     * {@inheritdoc}
     */
    final public function exchangeArray($input): void
    {
        $newInput = [];
        foreach ($input as $index => $value) {
            $newInput[$this->createValidIndex($index, $value)] = $value;
        }

        parent::exchangeArray($newInput);
    }

    /**
     * {@inheritdoc}
     */
    final public function offsetSet($index, $value): void
    {
        parent::offsetSet($this->createValidIndex($index, $value), $value);
    }

    /**
     * {@inheritdoc}
     */
    final protected function validateValue($value): void
    {
        if (!\is_object($value)) {
            throw InvalidArgumentException::expectedObject($this, \gettype($value));
        }

        $allowedInstance = $this->allowedInstance();
        if (!($value instanceof $allowedInstance)) {
            throw InvalidArgumentException::expectedInstance($this, $allowedInstance, \get_class($value));
        }
    }

    /**
     * @return string The allowed object instance the ObjectCollection supports.
     */
    abstract protected function allowedInstance(): string;

    /**
     * @param mixed $index
     * @param mixed $value
     *
     * @return string|int The index key which is valid depending on the setFlags.
     */
    protected function createValidIndex($index, $value)
    {
        if (\ArrayObject::ARRAY_AS_PROPS !== ($this->getFlags() & \ArrayObject::ARRAY_AS_PROPS)) {
            return $index;
        }

        if (null === $index) {
            $index = 0;
        }

        $newIndex = $index;
        if (\is_numeric($index) && \is_object($value)) {
            $newIndex = 'property_' . $index;
            while (isset($this->toArray()[$newIndex])) {
                $newIndex = 'property_' . $index++;
            }
        }

        return $newIndex;
    }
}
