<?php declare(strict_types=1);

namespace Aeviiq\Collection\Exception;

class LogicException extends \LogicException implements ExceptionInterface
{
    public static function oneResultExpected(string $cause): self
    {
        return new self(\sprintf('Exactly 1 result is expected in "%s", but none were found.', $cause));
    }

    public static function oneOrNullResultExpected(string $cause): self
    {
        return new self(\sprintf('Multiple results found but only 1 or null was expected in "%s".', $cause));
    }
}
