<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Base
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Base\Storage;

use Phoole\Base\Exception\NotFoundException;

/**
 * StorageInterface
 *
 * Temp storage
 *
 * @package Phoole\Base
 */
interface StorageInterface
{
    /**
     * Fetches a string value from the storage.
     *
     * @param  string $key  The unique key of this item in the storage.
     * @return array          [result_string, expiration_time]
     * @throws NotFoundException
     */
    public function get(string $key): array;

    /**
     * Save string into the storage with expiration time
     *
     * @param  string $key    The key of the item to store.
     * @param  string $value  The value
     * @param  int    $ttl    The TTL value of this item in seconds
     * @return bool     True on success and false on failure.
     */
    public function set(string $key, string $value, int $ttl): bool;

    /**
     * Delete an item from the storage
     *
     * @param  string $key  The unique key of the item to delete.
     * @return bool  True if the item was successfully removed. False if there was an error.
     */
    public function delete(string $key): bool;

    /**
     * Wipe the entire storage
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool;

    /**
     * garbage collection. Remove staled items
     *
     * @return void
     */
    public function garbageCollect();
}
