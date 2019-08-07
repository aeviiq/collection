<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use ArrayObject;

abstract class AbstractObjectCollection extends AbstractCollection
{
    protected const PROPERTY_NAME_PREFIX = '_';

    public function __construct(array $elements = [], string $iteratorClass = \ArrayIterator::class)
    {
        parent::__construct($this->indexToPropertyNameForArray($elements), $iteratorClass);
    }

    /**
     * {@inheritDoc}
     */
    public function natcasesort(): void
    {
        $this->throwExceptionIfToStringDoesNotExists();
        parent::natcasesort();
    }

    /**
     * {@inheritDoc}
     */
    public function natsort(): void
    {
        $this->throwExceptionIfToStringDoesNotExists();
        parent::natsort();
    }

    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $elements): void
    {
        parent::exchangeArray($this->indexToPropertyNameForArray($elements));
    }

    /**
     * {@inheritDoc}
     */
    public function append($element): void
    {
        $this->validateElement($element);
        $index = $this->indexToPropertyName(null, $this->getKeys());
        $this->offsetSet($index, $element);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return parent::offsetExists($this->indexToPropertyName($offset));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        parent::offsetGet($this->indexToPropertyName($offset));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $element): void
    {
        parent::offsetSet($this->indexToPropertyName($offset), $element);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        parent::offsetUnset($this->indexToPropertyName($offset));
    }

    /**
     * @return string The allowed class instance this collection supports.
     */
    abstract protected function allowedInstance(): string;

    /**
     * {@inheritDoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_object($element)) {
            throw InvalidArgumentException::expectedObject($this, \gettype($element));
        }

        $allowedInstance = $this->allowedInstance();
        if (!($element instanceof $allowedInstance)) {
            throw InvalidArgumentException::expectedInstance($this, $allowedInstance, \get_class($element));
        }
    }

    /**
     * @param mixed[] $elements
     * @param string  $iteratorClass
     */
    protected function createStorage(array $elements, string $iteratorClass): \ArrayObject
    {
        return new \ArrayObject($elements, ArrayObject::ARRAY_AS_PROPS, $iteratorClass);
    }

    /**
     * @throws LogicException
     */
    protected function throwExceptionIfToStringDoesNotExists(): void
    {
        $class = $this->allowedInstance();
        if (!(new \ReflectionClass($class))->hasMethod('__toString')) {
            throw new LogicException(\sprintf('"%s" must implement __toString() in order to natsort().', $class));
        }
    }

    /**
     * @param array $elements
     *
     * @return mixed[] Containing indexes which are valid property names. (@see https://www.php.net/manual/en/language.variables.basics.php)
     */
    protected function indexToPropertyNameForArray(array $elements): array
    {
        $result = [];
        foreach ($elements as $index => $element) {
            $result[$this->indexToPropertyName($index)] = $element;
        }

        return $result;
    }

    /**
     * @param int|string $input
     * @param mixed[]    $existingIndexes
     *
     * @return string That is a valid property name. (@see https://www.php.net/manual/en/language.variables.basics.php)
     */
    protected function indexToPropertyName($input, array $existingIndexes = []): string
    {
        $existingIndexes = \array_flip($existingIndexes);
        if (\is_string($input) && \ctype_alnum(\str_replace(static::PROPERTY_NAME_PREFIX, '', $input))) {
            if (empty($existingIndexes)) {
                return $input;
            }

            $i = 0;
            $index = $input . $i;
            while (isset($existingIndexes[$index])) {
                $index = $input . ++$i;
            }

            return $index;
        }

        if (null === $input) {
            $input = 0;
        }

        if (\is_int($input) && $input >= 0) {
            $index = static::PROPERTY_NAME_PREFIX . $input;
            if (empty($existingIndexes)) {
                return $index;
            }

            while (isset($existingIndexes[$index])) {
                $index = static::PROPERTY_NAME_PREFIX . ++$input;
            }

            return $index;
        }

        throw new InvalidArgumentException(\sprintf('A property name must be an alphanumeric string or an integer >= 0. "%s" given.', $input));
    }
}
