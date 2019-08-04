<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

abstract class AbstractCollection extends \ArrayObject implements CollectionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        array $elements = [],
        int $flags = \ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS,
        string $iteratorClass = \ArrayIterator::class
    ) {
        $this->validateArray($elements);
        parent::__construct($elements, $flags, $iteratorClass);
    }

    /**
     * @return CollectionInterface|static
     */
    public function exchangeArray($input): CollectionInterface
    {
        $this->validateArray($input);

        return $this->createFrom(parent::exchangeArray($input));
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $value): void
    {
        $this->validateValue($value);

        parent::offsetSet($index, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element): bool
    {
        return \in_array($element, $this->toArray(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->exchangeArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys(): array
    {
        return \array_keys($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): array
    {
        return \array_values($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        return $this->createFrom(\array_slice($this->toArray(), $offset, $length, true));
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        $elements = $this->toArray();
        return \array_shift($elements);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function map(\Closure $closure): array
    {
        return \array_map($closure, $this->toArray());
    }

    /**
     * @return static|CollectionInterface
     */
    public function filter(\Closure $closure): CollectionInterface
    {
        return $this->createFrom(\array_filter($this->toArray(), $closure, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @see https://github.com/aeviiq/collection/issues/32
     *
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        $iterator = $this->getIteratorClass();
        return new $iterator($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function getOneBy(\Closure $closure)
    {
        $result = $this->getOneOrNullBy($closure);
        if (null === $result) {
            throw LogicException::oneResultExpected(static::class);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getOneOrNullBy(\Closure $closure)
    {
        $filteredResult = $this->filter($closure);

        if ($filteredResult->count() > 1) {
            throw LogicException::oneOrNullResultExpected(static::class);
        }

        return $filteredResult->first();
    }

    /**
     * @param mixed $value
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
     *
     * @return CollectionInterface|static
     */
    protected function createFrom(array $elements): CollectionInterface
    {
        return new static($elements, $this->getFlags(), $this->getIteratorClass());
    }
}
