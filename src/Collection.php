<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

/**
 * @psalm-template TKey as array-key
 * @psalm-template TValue
 * @phpstan-template TKey
 * @phpstan-template TValue
 *
 * @psalm-implements CollectionInterface<TKey, TValue>
 * @phpstan-implements CollectionInterface<TKey, TValue>
 */
class Collection implements CollectionInterface
{
    /**
     * @psalm-var array<TKey, TValue>
     * @phpstan-var array<TKey, TValue>
     *
     * @var array<string|int, mixed>
     */
    private $elements;

    /**
     * @psalm-var class-string<\ArrayAccess>|string
     * @phpstan-var class-string<\ArrayAccess>|string
     *
     * @var string
     */
    private $iteratorClass;

    /**
     * @psalm-param array<TKey, TValue> $elements
     * @phpstan-param array<TKey, TValue> $elements
     *
     * @psalm-param class-string<\ArrayAccess>|string $iteratorClass
     * @phpstan-param class-string<\ArrayAccess>|string $iteratorClass
     *
     * @param array<string|int, mixed> $elements
     * @param string $iteratorClass
     */
    final public function __construct(array $elements = [], string $iteratorClass = \ArrayIterator::class)
    {
        $this->validateElements($elements);
        $this->elements = $elements;
        $this->setIteratorClass($iteratorClass);
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        $elements = $this->toArray();
        return \array_shift($elements);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function remove($element): void
    {
        /**
         * @psalm-var TKey $key
         * @phpstan-var TKey $key
         */
        $key = \array_search($element, $this->elements, true);
        if (false === $key) {
            return;
        }

        $this->offsetUnset($key);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * {@inheritDoc}
     */
    public function map(\Closure $closure): array
    {
        return \array_map($closure, $this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function filter(\Closure $closure): CollectionInterface
    {
        return $this->createFrom(\array_filter($this->elements, $closure, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * {@inheritDoc}
     */
    public function merge($input): void
    {
        $elements = ($input instanceof static) ? $input->toArray() : $input;
        if (!\is_array($elements)) {
            throw new InvalidArgumentException(\sprintf('"%s" can only merge with an array or instance of itself.', static::class));
        }

        $this->exchangeArray(\array_merge($this->elements, $elements));
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element): bool
    {
        return \in_array($element, $this->elements, true);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->elements = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys(): array
    {
        /**
         * @psalm-var array<int, TKey>
         * @phpstan-var array<int, TKey>
         */
        return \array_keys($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function getValues(): array
    {
        return \array_values($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function getOneBy(\Closure $closure)
    {
        $result = $this->getOneOrNullBy($closure);
        if (null === $result) {
            throw new LogicException('No results found, one expected.');
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getOneOrNullBy(\Closure $closure)
    {
        $filteredResult = $this->filter($closure);

        if ($filteredResult->count() > 1) {
            throw new LogicException('Multiple results found, one or null expected.');
        }

        return $filteredResult->first();
    }

    /**
     * {@inheritDoc}
     */
    public function exchangeArray(array $elements): void
    {
        $this->validateElements($elements);
        $this->elements = $elements;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $iteratorClass = $this->iteratorClass;

        return new $iteratorClass($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function setIteratorClass(string $iteratorClass): void
    {
        if (!\is_a($iteratorClass, \ArrayAccess::class, true)) {
            throw new InvalidArgumentException(\sprintf('Iterator class must implement "%s".', \ArrayAccess::class));
        }

        $this->iteratorClass = $iteratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function append($element): void
    {
        $this->validateElement($element);
        $this->elements[] = $element;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->elements[$offset]) || array_key_exists($offset, $this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->elements[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->validateElement($value);

        if (null === $offset) {
            $this->elements[] = $value;

            return;
        }

        $this->elements[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->elements[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function copy(): CollectionInterface
    {
        return clone $this;
    }

    /**
     * {@inheritDoc}
     */
    public function asort(): void
    {
        \asort($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function ksort(): void
    {
        \ksort($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function natcasesort(): void
    {
        \natcasesort($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function natsort(): void
    {
        \natsort($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function uasort(callable $func): void
    {
        \uasort($this->elements, $func);
    }

    /**
     * {@inheritDoc}
     */
    public function uksort(callable $func): void
    {
        \uksort($this->elements, $func);
    }

    /**
     * @psalm-param TValue $element
     * @phpstan-param TValue $element
     *
     * @param mixed $element
     *
     * @throws InvalidArgumentException When the given element is not of the expected type.
     */
    protected function validateElement($element): void
    {
    }

    /**
     * @psalm-param array<TKey, TValue> $elements
     * @phpstan-param array<TKey, TValue> $elements
     *
     * @param array<string|int, mixed> $elements
     *
     * @psalm-return self<TKey, TValue>
     * @phpstan-return self<TKey, TValue>
     */
    protected function createFrom(array $elements): self
    {
        return new static($elements, $this->iteratorClass);
    }

    /**
     * @psalm-param array<TKey, TValue> $elements
     * @phpstan-param array<TKey, TValue> $elements
     *
     * @param array<string|int, mixed> $elements
     *
     * @throws InvalidArgumentException When one of the given elements is not of the expected type.
     */
    protected function validateElements(array $elements): void
    {
        foreach ($elements as $element) {
            $this->validateElement($element);
        }
    }
}
