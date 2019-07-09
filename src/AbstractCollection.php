<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

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

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    public function first()
    {
        $elements = $this->toArray();
        return \array_shift($elements);
    }

    public function last()
    {
        $elements = $this->toArray();
        $last = \end($elements);
        if (false === $last) {
            return null;
        }

        return $last;
    }

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

    /**
     * @return static|Collection
     */
    public function filter(\Closure $closure): Collection
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

    protected function createFrom(array $elements): Collection
    {
        return new static($elements, $this->getFlags(), $this->getIteratorClass());
    }
}
