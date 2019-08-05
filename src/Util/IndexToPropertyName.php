<?php declare(strict_types=1);

namespace Aeviiq\Collection\Util;

use Aeviiq\Collection\Exception\InvalidArgumentException;

final class IndexToPropertyName
{
    private const PROPERTY_PREFIX = '_';

    /**
     * @param int|string $input
     * @param mixed[]    $existingIndexes
     * @param bool       $unique
     *
     * @return string That is a valid property name. (@see https://www.php.net/manual/en/language.variables.basics.php)
     */
    public static function forSingle($input, array $existingIndexes = []): string
    {
        $existingIndexes = \array_flip($existingIndexes);
        if (\is_string($input) && \ctype_alnum(\str_replace(static::PROPERTY_PREFIX, '', $input))) {
            if (empty($existingIndexes)) {
                return $input;
            }

            $i = 0;
            $index = $input . $i;
            while (isset($existingIndexes[$index])) {
                $index = $input . ++$i;
            }

            return $index;
        }

        if (null === $input) {
            $input = 0;
        }

        if (\is_int($input) && $input >= 0) {
            $index = static::PROPERTY_PREFIX . $input;
            if (empty($existingIndexes)) {
                return $index;
            }

            while (isset($existingIndexes[$index])) {
                $index = static::PROPERTY_PREFIX . ++$input;
            }

            return $index;
        }

        throw new InvalidArgumentException(\sprintf('A property name must be an alphanumeric string or an integer >= 0. "%s" given.', $input));
    }

    /**
     * @param mixed[] $elements
     * @param mixed[] $existingIndexes
     *
     * @return mixed[] Containing indexes which are valid property names. (@see https://www.php.net/manual/en/language.variables.basics.php)
     */
    public static function forMultiple(array $elements, array $existingIndexes = [], bool $unique = false): array
    {
        $result = [];
        foreach ($elements as $index => $value) {
            $propertyName = static::forSingle($index, $existingIndexes);
            if ($unique) {
                $existingIndexes[] = $propertyName;
            }
            $result[$propertyName] = $value;
        }

        return $result;
    }
}
