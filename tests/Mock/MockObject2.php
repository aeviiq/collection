<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection\Mock;

final class MockObject2 implements MockObjectInterface
{
    public function getText(): string
    {
        return 'mock_2';
    }

    public function getInt(): int
    {
        return 2;
    }
}
