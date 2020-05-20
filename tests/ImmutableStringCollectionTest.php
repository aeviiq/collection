<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\ImmutableStringCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ImmutableStringCollectionTest extends TestCase
{
    public function testCreate(): void
    {
        $collection = new ImmutableStringCollection(['string']);
        self::assertCount(1, $collection);
    }

    public function testCreateWithInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "string", "integer" given.', ImmutableStringCollection::class)
        );
        new ImmutableStringCollection([1]);
    }

    public function testCreateWithDouble(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "string", "double" given.', ImmutableStringCollection::class)
        );
        new ImmutableStringCollection([1.1]);
    }

    public function testCreateWithObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "string", "object" given.', ImmutableStringCollection::class)
        );
        new ImmutableStringCollection([new stdClass()]);
    }
}
