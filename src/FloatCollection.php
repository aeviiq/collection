<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends Collection<array-key, float>
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
