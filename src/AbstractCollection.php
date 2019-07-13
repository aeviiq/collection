<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

abstract class AbstractCollection extends \ArrayObject implements CollectionInterface
{
    /**
     * @inheritdoc
     */
    public function __construct(
        array $elements = [],
        int $flags = \ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS,
        string $iteratorClass = \ArrayIterator::class
    ) {
        parent::__construct([], $flags, $iteratorClass);
        foreach ($elements as $key => $element) {
            $this->offsetSet($key, $element);
        }
    }

    /**
     * @inheritdoc
     */
    final public function exchangeArray($input): void
    {
        $this->validateArray($input);

        parent::exchangeArray($input);
    }

    /**
     * @inheritdoc
     */
    final public function offsetSet($index, $value): void
    {
        $this->validateValue($value);

        parent::offsetSet($index, $value);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @inheritdoc
     */
    public function merge($input): void
    {
        if (\is_array($input)) {
            $this->exchangeArray(\array_merge($this->toArray(), $input));

            return;
        }

        if ($input instanceof static) {
            $this->exchangeArray(\array_merge($this->toArray(), $input->toArray()));

            return;
        }

        throw new InvalidArgumentException(\sprintf('"%s" can only merge with an array or instance of itself.', static::class));
    }

    /**
     * @inheritdoc
     */
    public function first()
    {
        $elements = $this->toArray();
        return \array_shift($elements);
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        $elements = $this->toArray();
        $last = \end($elements);
        if (false === $last) {
            return null;
        }

        return $last;
    }

    /**
     * @inheritdoc
     */
    public function remove($element): void
    {
        $key = \array_search($element, $this->toArray(), true);
        if (false === $key) {
            return;
        }
        $this->offsetUnset($key);
    }

    /**
     * @return mixed[]
     */
    public function map(\Closure $closure): array
    {
        return \array_map($closure, $this->toArray());
    }

    public function filter(\Closure $closure): CollectionInterface
    {
        return $this->createFrom(\array_filter($this->toArray(), $closure, ARRAY_FILTER_USE_BOTH));
    }

    public function getOneBy(\Closure $closure)
    {
        $result = $this->getOneOrNullBy($closure);
        if (null === $result) {
            throw LogicException::oneResultExpected(static::class);
        }

        return $result;
    }

    public function getOneOrNullBy(\Closure $closure)
    {
        $filteredResult = $this->filter($closure);

        if ($filteredResult->count() > 1) {
            throw LogicException::oneOrNullResultExpected(static::class);
        }

        return $filteredResult->first();
    }

    /**
     * @param mixed $element
     *
     * @throws InvalidArgumentException Thrown when the given values are not of the expected type.
     */
    abstract protected function validateValue($value): void;

    /**
     * @param mixed[] $elements
     */
    protected function validateArray(array $elements): void
    {
        foreach ($elements as $element) {
            $this->validateValue($element);
        }
    }

    /**
     * @param mixed[] $elements
     */
    protected function createFrom(array $elements): CollectionInterface
    {
        return new static($elements, $this->getFlags(), $this->getIteratorClass());
    }
}
