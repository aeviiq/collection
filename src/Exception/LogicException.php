<?php declare(strict_types = 1);

namespace Aeviiq\Collection\Exception;

final class LogicException extends \LogicException implements Throwable
{
    public static function oneResultExpected(string $cause): LogicException
    {
        return new static(\sprintf('Exactly 1 result is expected in %s, but none were found.', $cause));
    }

    public static function oneOrNullResultExpected(string $cause): LogicException
    {
        return new static(\sprintf('Multiple results found but only 1 or null was expected in %s.', $cause));
    }
}
