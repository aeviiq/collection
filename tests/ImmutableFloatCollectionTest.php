<?php

declare(strict_types=1);

namespace Aeviiq\Collection\Tests;

use Aeviiq\Collection\Exception\InvalidArgumentException;
use Aeviiq\Collection\ImmutableFloatCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ImmutableFloatCollectionTest extends TestCase
{
    public function testCreate(): void
    {
        $collection = new ImmutableFloatCollection([1.1]);
        self::assertCount(1, $collection);
    }

    public function testCreateWithInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "float", "integer" given.', ImmutableFloatCollection::class)
        );
        new ImmutableFloatCollection([1]);
    }

    public function testCreateWithString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "float", "string" given.', ImmutableFloatCollection::class)
        );
        new ImmutableFloatCollection(['string']);
    }

    public function testCreateWithObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('"%s" only allows elements of type "float", "object" given.', ImmutableFloatCollection::class)
        );
        new ImmutableFloatCollection([new stdClass()]);
    }
}
