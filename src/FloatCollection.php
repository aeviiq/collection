<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends Collection<array-key, float>
 * @phpstan-extends Collection<array-key, float>
 *
 * @method \ArrayIterator|array<string|int, float> getIterator()
 * @method float|null first()
 * @method float|null last()
 */
class FloatCollection extends Collection
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
