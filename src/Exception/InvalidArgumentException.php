<?php declare(strict_types = 1);

namespace Aeviiq\Collection\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements IException
{
    public static function invalidValue(
        string $expectedType,
        string $givenType,
        string $parameterName = '$value'
    ): InvalidArgumentException {
        return new static(\sprintf('%s must be %s, %s given.', $parameterName, $expectedType, $givenType));
    }
}
