<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

class Collection implements CollectionInterface
{
    /**
     * @var mixed[]
     */
    private $elements;

    /**
     * @var string
     */
    private $iteratorClass;

    /**
     * @param mixed[] $elements
     * @param string  $iteratorClass
     */
    public function __construct(array $elements = [], string $iteratorClass = \ArrayIterator::class)
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
     *
     * @return CollectionInterface|static
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
    public function offsetSet($offset, $element): void
    {
        $this->validateElement($element);
        $this->elements[$offset] = $element;
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
     * @param mixed $element
     *
     * @throws InvalidArgumentException When the given element is not of the expected type.
     */
    protected function validateElement($element): void
    {
    }

    /**
     * @param mixed[] $elements
     */
    protected function createFrom(array $elements): self
    {
        return new static($elements, $this->iteratorClass);
    }

    /**
     * @param mixed[] $elements
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
