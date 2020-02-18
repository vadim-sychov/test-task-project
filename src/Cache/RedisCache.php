<?php
declare(strict_types=1);

namespace App\Cache;

use Redis;

/**
 * This class is used for storing data in cache
 */
class RedisCache implements CacheInterface
{
    private const USERS_INCR_KEY = 'users:incr';
    private const TRACKING_DATA_INCR_KEY = 'tracking-data:incr';

    /** @var Redis */
    private $redis;

    /**
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @inheritDoc
     */
    public function getUsersAutoincrement(): int
    {
        return $this->redis->incr(self::USERS_INCR_KEY);
    }

    /**
     * @inheritDoc
     */
    public function getTrackingDataAutoincrement(): int
    {
        return $this->redis->incr(self::TRACKING_DATA_INCR_KEY);
    }
}