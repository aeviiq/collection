<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends Collection<array-key, int>
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
