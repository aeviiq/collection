<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;
use Aeviiq\Collection\Util\IndexToPropertyName;
use ArrayObject;

abstract class AbstractObjectCollection extends AbstractCollection
{
    public function __construct(array $elements = [], string $iteratorClass = \ArrayIterator::class)
    {
        parent::__construct(IndexToPropertyName::forMultiple($elements), $iteratorClass);
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
        parent::exchangeArray(IndexToPropertyName::forMultiple($elements));
    }

    /**
     * {@inheritDoc}
     */
    public function append($element): void
    {
        $this->validateElement($element);
        $index = IndexToPropertyName::forSingle(null, $this->getKeys());
        $this->offsetSet($index, $element);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return parent::offsetExists(IndexToPropertyName::forSingle($offset));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        parent::offsetGet(IndexToPropertyName::forSingle($offset));
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $element): void
    {
        parent::offsetSet(IndexToPropertyName::forSingle($offset), $element);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        parent::offsetUnset(IndexToPropertyName::forSingle($offset));
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
}
