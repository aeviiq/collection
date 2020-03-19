<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends Collection<array-key, int>
 * @phpstan-extends Collection<array-key, int>
 *
 * @method \ArrayIterator|array<string|int, int> getIterator()
 * @method int|null first()
 * @method int|null last()
 */
class IntCollection extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_int($element)) {
            throw InvalidArgumentException::expectedInt($this, \gettype($element));
        }
    }
}
