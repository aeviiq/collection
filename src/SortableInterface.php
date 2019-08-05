<?php declare(strict_types=1);

namespace Aeviiq\Collection;

interface SortableInterface
{
    /**
     * @see https://www.php.net/manual/en/arrayobject.asort.php
     */
    public function asort(): void;

    /**
     * @see https://www.php.net/manual/en/arrayobject.ksort.php
     */
    public function ksort(): void;

    /**
     * @see https://www.php.net/manual/en/arrayobject.natcasesort.php
     */
    public function natcasesort(): void;

    /**
     * @see https://www.php.net/manual/en/arrayobject.natsort.php
     */
    public function natsort(): void;

    /**
     * @see https://www.php.net/manual/en/arrayobject.uasort.php
     */
    public function uasort(callable $func): void;

    /**
     * @see https://www.php.net/manual/en/arrayobject.uksort.php
     */
    public function uksort(callable $func): void;
}
