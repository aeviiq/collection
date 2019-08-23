<?php declare(strict_types=1);

namespace Aeviiq\Collection;

interface SortableInterface
{
    /**
     * @see https://www.php.net/manual/en/function.asort.php
     */
    public function asort(): void;

    /**
     * @see https://www.php.net/manual/en/function.ksort.php
     */
    public function ksort(): void;

    /**
     * @see https://www.php.net/manual/en/function.natcasesort.php
     */
    public function natcasesort(): void;

    /**
     * @see https://www.php.net/manual/en/function.natsort.php
     */
    public function natsort(): void;

    /**
     * @see https://www.php.net/manual/en/function.uasort.php
     */
    public function uasort(callable $func): void;

    /**
     * @see https://www.php.net/manual/en/function.uksort.php
     */
    public function uksort(callable $func): void;
}
