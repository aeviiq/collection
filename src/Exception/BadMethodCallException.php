<?php declare(strict_types=1);

namespace Aeviiq\Collection\Exception;

class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface
{
    public static function immutable(): BadMethodCallException
    {
        return new static('Immutable collection should not be modified.');
    }
}
