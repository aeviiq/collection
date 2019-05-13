<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

abstract class Collection extends \ArrayObject implements ICollection
{
    public function __construct(
        array $input = [],
        int $flags = \ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS,
        string $iteratorClass = \ArrayIterator::class
    ) {
        parent::__construct([], $flags, $iteratorClass);
        foreach ($input as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    public function contains($element): bool
    {
        return \in_array($element, $this->getArrayCopy(), true);
    }

    public function remove($key): void
    {
        $elements = $this->getArrayCopy();
        if (!isset($elements[$key]) && !\array_key_exists($key, $elements)) {
            return;
        }

        $this->offsetUnset($key);
    }

    public function removeElement($element): void
    {
        $elements = $this->getArrayCopy();
        $key = \array_search($element, $elements, true);

        if (false === $key) {
            return;
        }

        $this->offsetUnset($key);
    }

    public function clear(): void
    {
        $this->exchangeArray([]);
    }

    public function filter(callable $closure): ICollection
    {
        return new static(\array_filter($this->getArrayCopy(), $closure));
    }

    public function isEmpty(): bool
    {
        return empty($this->getArrayCopy());
    }

    public function copy(): ICollection
    {
        return new static($this->getArrayCopy());
    }

    public function first()
    {
        $elements = $this->getArrayCopy();
        $first = \reset($elements);
        if (false === $first) {
            return null;
        }

        return $first;
    }

    public function last()
    {
        $elements = $this->getArrayCopy();
        $last = \end($elements);
        if (false === $last) {
            return null;
        }

        return $last;
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
