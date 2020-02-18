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
}