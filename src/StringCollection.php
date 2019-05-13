<?php declare(strict_types = 1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Traversable;

/**
 * @method Traversable|string[] getIterator
 * @method string|null first
 * @method string|null last
 */
final class StringCollection extends Collection
{
    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException Thrown when the given $value is not of the expected type.
     */
    public function offsetSet($index, $value): void
    {
        if (!is_string($value)) {
            throw InvalidArgumentException::invalidValue('a string', gettype($value));
        }

        parent::offsetSet($index, $value);
    }
}
