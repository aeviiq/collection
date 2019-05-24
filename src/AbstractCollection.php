<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractCollection extends ArrayCollection
{
    /**
     * @inheritdoc
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();
        foreach ($elements as $key => $element) {
            $this->set($key, $element);
        }
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value): void
    {
        $this->typeCheck($value);
        parent::set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function add($element): bool
    {
        $this->typeCheck($element);

        return parent::add($element);
    }

    public function first()
    {
        $first = parent::first();
        if (false === $first) {
            return null;
        }

        return $first;
    }

    public function last()
    {
        $last = parent::last();
        if (false === $last) {
            return null;
        }

        return $last;
    }

    /**
     * @param mixed $element
     *
     * @throws InvalidArgumentException When the element is not of the expected type.
     */
    abstract protected function typeCheck($element): void;
}
