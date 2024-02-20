<?php 

declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends Collection<array-key, string>
 */
class StringCollection extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected function validateElement($element): void
    {
        if (!\is_string($element)) {
            throw InvalidArgumentException::expectedString($this, \gettype($element));
        }
    }
}
