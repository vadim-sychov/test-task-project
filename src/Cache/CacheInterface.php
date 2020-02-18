<?php
declare(strict_types=1);

namespace App\Cache;

/**
 * This interface is used to abstract from the cache layer
 */
interface CacheInterface
{
    /**
     * Get autoincrement from cache for users storage
     *
     * @return int
     */
    public function getUsersAutoincrement(): int;

    /**
     * Get autoincrement from cache for tracking-data storage
     *
     * @return int
     */
    public function getTrackingDataAutoincrement(): int;

    /**
     * Save string data to cache storage using token as key
     *
     * @param string $token
     * @param string $data
     */
    public function saveDataByToken(string $token, string $data): void;

    /**
     * Get string data from cache using token as key
     *
     * @param string $token
     * @return null|string
     */
    public function getDataByToken(string $token): ?string;
}