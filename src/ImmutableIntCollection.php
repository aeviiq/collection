<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends         ImmutableCollection<array-key, int>
 * @phpstan-extends ImmutableCollection<array-key, int>
 *
 * @method \ArrayIterator|array<string|int, int> getIterator()
 * @method int|null first()
 * @method int|null last()
 */
class ImmutableIntCollection extends ImmutableCollection
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
