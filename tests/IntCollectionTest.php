<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\CollectionInterface;
use Aeviiq\Collection\IntCollection;

final class IntCollectionTest extends AbstractCollectionTest
{
    protected function isValid($value): bool
    {
        return \is_int($value);
    }
    
    protected function getCollection(array $elements = []): CollectionInterface
    {
        return new IntCollection($elements);
    }
    
    protected function getValidElement(): int
    {
        return 0;
    }
}
