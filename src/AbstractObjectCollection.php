<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Util\IndexToPropertyName;

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
        parent::__construct(IndexToPropertyName::forMultiple($elements), \ArrayObject::ARRAY_AS_PROPS, $iteratorClass);
    }

    /**
     * @return CollectionInterface|static
     */
    public function exchangeArray($input): CollectionInterface
    {
        return parent::exchangeArray(IndexToPropertyName::forMultiple($input));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $value): void
    {
        if (null === $index) {
            parent::offsetSet(IndexToPropertyName::forSingle($index, $this->getKeys(), true), $value);

            return;
        }

        parent::offsetSet(IndexToPropertyName::forSingle($index), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($index): bool
    {
        return parent::offsetExists(IndexToPropertyName::forSingle($index));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($index): void
    {
        parent::offsetUnset(IndexToPropertyName::forSingle($index));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($index)
    {
        return parent::offsetGet(IndexToPropertyName::forSingle($index));
    }

    /**
     * {@inheritdoc}
     */
    protected function validateValue($value): void
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
     * @return CollectionInterface|static
     */
    protected function createFrom(array $elements): CollectionInterface
    {
        $instance = new static($elements, $this->getIteratorClass());
        $instance->setFlags($this->getFlags());

        return $instance;
    }
}
