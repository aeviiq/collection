<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\CollectionInterface;
use Aeviiq\Collection\StringCollection;

final class StringCollectionTest extends AbstractCollectionTest
{
    protected function isValid($value): bool
    {
        return \is_string($value);
    }
    
    protected function getCollection(array $elements = []): CollectionInterface
    {
        return new StringCollection($elements);
    }
}
