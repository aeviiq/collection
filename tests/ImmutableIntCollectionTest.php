<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\ImmutableIntCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ImmutableIntCollectionTest extends TestCase
{
    public function testCreate(): void
    {
        $collection = new ImmutableIntCollection([1]);
        self::assertCount(1, $collection);
    }

    public function testCreateWithString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "integer", "string" given.', ImmutableIntCollection::class)
        );
        new ImmutableIntCollection(['string']);
    }

    public function testCreateWithDouble(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "integer", "double" given.', ImmutableIntCollection::class)
        );
        new ImmutableIntCollection([1.1]);
    }

    public function testCreateWithObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "integer", "object" given.', ImmutableIntCollection::class)
        );
        new ImmutableIntCollection([new stdClass()]);
    }
}
