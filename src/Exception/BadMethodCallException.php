<?php 

declare(strict_types=1);

namespace Aeviiq\Collection\Exception;

class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface
{
    public static function immutable(): self
    {
        return new self('Immutable collection should not be modified.');
    }
}
