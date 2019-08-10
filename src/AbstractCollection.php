<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\Exception\LogicException;

abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var \ArrayObject
     */
    private $storage;

    /**
     * @param mixed[] $elements
     * @param string  $iteratorClass
     */
    public function __construct(array $elements = [], string $iteratorClass = \ArrayIterator::class)
    {
        $this->validateElements($elements);
        $this->storage = $this->createStorage($elements, $iteratorClass);
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        $elements = $this->storage->getArrayCopy();

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
        $key = \array_search($element, $this->toArray(), true);
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
        return $this->storage->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     */
    public function map(\Closure $closure): array
    {
        return \array_map($closure, $this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function filter(\Closure $closure): CollectionInterface
    {
        return $this->createFrom(\array_filter($this->toArray(), $closure, ARRAY_FILTER_USE_BOTH));
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

        $this->exchangeArray(\array_merge($this->toArray(), $elements));
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
        return \in_array($element, $this->toArray(), true);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $this->storage->exchangeArray([]);
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys(): array
    {
        return \array_keys($this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function getValues(): array
    {
        return \array_values($this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        return $this->createFrom(\array_slice($this->toArray(), $offset, $length, true));
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
        $this->storage->exchangeArray($elements);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $iteratorClass = $this->storage->getIteratorClass();

        return new $iteratorClass($this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function append($element): void
    {
        $this->validateElement($element);
        $this->storage->append($element);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->storage->offsetExists($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        $this->storage->offsetGet($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $element): void
    {
        $this->validateElement($element);
        $this->storage->offsetSet($offset, $element);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        $this->storage->offsetUnset($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->storage->count();
    }

    /**
     * {@inheritDoc}
     */
    public function asort(): void
    {
        $this->storage->asort();
    }

    /**
     * {@inheritDoc}
     */
    public function ksort(): void
    {
        $this->storage->ksort();
    }

    /**
     * {@inheritDoc}
     */
    public function natcasesort(): void
    {
        $this->storage->natcasesort();
    }

    /**
     * {@inheritDoc}
     */
    public function natsort(): void
    {
        $this->storage->natsort();
    }

    /**
     * {@inheritDoc}
     */
    public function uasort(callable $func): void
    {
        $this->storage->uasort($func);
    }

    /**
     * {@inheritDoc}
     */
    public function uksort(callable $func): void
    {
        $this->storage->uksort($func);
    }

    /**
     * @param mixed $element
     *
     * @throws InvalidArgumentException When the given element is not of the expected type.
     */
    abstract protected function validateElement($element): void;

    /**
     * @param mixed[] $elements
     * @param string  $iteratorClass
     */
    protected function createStorage(array $elements, string $iteratorClass): \ArrayObject
    {
        return new \ArrayObject($elements, 0, $iteratorClass);
    }

    protected function getStorage(): \ArrayObject
    {
        return $this->storage;
    }

    /**
     * @param mixed[] $elements
     */
    protected function createFrom(array $elements): self
    {
        return new static($elements, $this->storage->getIteratorClass());
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
