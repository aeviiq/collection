<?php

declare(strict_types=1);

namespace Aeviiq\Tests\Collection\Mock;

use Aeviiq\Collection\AbstractObjectCollection;

final class MockObjectCollection extends AbstractObjectCollection
{
    /**
     * @return string The allowed object instance the ObjectCollection supports.
     */
    protected function allowedInstance(): string
    {
        return MockObjectInterface::class;
    }
}
