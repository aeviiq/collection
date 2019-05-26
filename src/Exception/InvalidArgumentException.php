<?php declare(strict_types = 1);

namespace Aeviiq\Collection\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements IException
{
    public static function expectedString(object $subject, string $givenType): InvalidArgumentException
    {
        return static::createExpectedTypeException($subject, $givenType, 'string');
    }

    public static function expectedInt(object $subject, string $givenType): InvalidArgumentException
    {
        return static::createExpectedTypeException($subject, $givenType, 'integer');
    }

    public static function expectedFloat(object $subject, string $givenType): InvalidArgumentException
    {
        return static::createExpectedTypeException($subject, $givenType, 'float');
    }

    public static function expectedObject(object $subject, string $givenType): InvalidArgumentException
    {
        return static::createExpectedTypeException($subject, $givenType, 'object');
    }

    public static function expectedInstance(object $subject, string $expected, string $givenType): InvalidArgumentException
    {
        return new static(\sprintf('"%s" only allows elements of that are an instanceof "%s", "%s" given.', \get_class($subject), $expected, $givenType));
    }

    private static function createExpectedTypeException(object $subject, string $givenType, string $type): InvalidArgumentException
    {
        return new static(\sprintf('"%s" only allows elements of type "%s", "%s" given.', \get_class($subject), $type, $givenType));
    }
}
