<?php declare(strict_types=1);

namespace Aeviiq\Collection\Util;

final class IndexGenerator
{
    /**
     * @param int|string $index
     * @param mixed[]    $existingIndexes
     * @param bool       $unique
     *
     * @return string That is a valid property name. (@see https://www.php.net/manual/en/language.variables.basics.php)
     */
    public static function createValidIndex($index, array $existingIndexes = [], bool $unique = false): string
    {
        $index = $index ?? 0;
        if (!\is_numeric($index)) {
            return $index;
        }

        $newIndex = '_' . $index;
        if (!$unique) {
            return $newIndex;
        }

        while (isset($existingIndexes[$newIndex])) {
            $newIndex = '_' . $index++;
        }

        return $newIndex;
    }

    /**
     * @param mixed[] $elements
     * @param mixed[] $existingIndexes
     *
     * @return mixed[] Containing indexes which are valid property names. (@see https://www.php.net/manual/en/language.variables.basics.php)
     */
    public static function createUniqueValidIndexesForArray(array $elements, array $existingIndexes): array
    {
        $result = [];
        foreach ($elements as $index => $value) {
            $result[static::createValidIndex($index, $existingIndexes, true)] = $value;
        }

        return $result;
    }
}
