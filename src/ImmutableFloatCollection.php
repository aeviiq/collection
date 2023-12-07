<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends         ImmutableCollection<array-key, float>
 * @phpstan-extends ImmutableCollection<array-key, float>
 *
 * @method \ArrayIterator|array<string|int, float> getIterator()
 * @method float|null first()
 * @method float|null last()
 */
class ImmutableFloatCollection extends ImmutableCollection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_float($element)) {
            throw InvalidArgumentException::expectedFloat($this, \gettype($element));
        }
    }
}
