<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @psalm-extends Collection<array-key, string>
 * @phpstan-extends Collection<array-key, string>
 *
 * @method \ArrayIterator|array<string|int, string> getIterator()
 * @method string|null first()
 * @method string|null last()
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
