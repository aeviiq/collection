<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection;

use Aeviiq\Collection\CollectionInterface;
use Aeviiq\Collection\FloatCollection;

final class FloatCollectionTest extends AbstractCollectionTest
{
    protected function isValid($value): bool
    {
        return \is_float($value);
    }
    
    protected function getCollection(array $elements = []): CollectionInterface
    {
        return new FloatCollection($elements);
    }
}
