<?php declare(strict_types=1);

namespace Aeviiq\Collection;

use Aeviiq\Collection\Exception\InvalidArgumentException;

/**
 * @extends         ImmutableCollection<array-key, string>
 * @phpstan-extends ImmutableCollection<array-key, string>
 *
 * @method \ArrayIterator|array<string|int, string> getIterator()
 * @method string|null first()
 * @method string|null last()
 */
class ImmutableStringCollection extends ImmutableCollection
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
