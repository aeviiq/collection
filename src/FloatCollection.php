<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @method \ArrayIterator|float[] getIterator
 * @method float|null first
 * @method float|null last
 */
final class FloatCollection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($value): void
    {
        if (!\is_float($value)) {
            throw InvalidArgumentException::expectedFloat($this, \gettype($value));
        }
    }
}
