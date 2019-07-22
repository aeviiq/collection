<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection\Mock;

final class MockObject1 implements MockObjectInterface
{
    public function getText(): string
    {
        return 'mock_1';
    }

    public function getInt(): int
    {
        return 1;
    }
}
