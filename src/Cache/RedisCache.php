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

    private const TOKEN_DATA_KEY = 'token-data:';

    /** @var Redis */
    private $redis;

    /** @var int */
    private $tokenCacheDataExpiresAt;

    /**
     * @param Redis $redis
     * @param int $tokenCacheDataExpiresAt
     */
    public function __construct(Redis $redis, int $tokenCacheDataExpiresAt)
    {
        $this->tokenCacheDataExpiresAt = $tokenCacheDataExpiresAt;
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

    /**
     * @inheritDoc
     */
    public function saveDataByToken(string $token, string $data): void
    {
        $this->redis->set(self::TOKEN_DATA_KEY . $token, $data, $this->tokenCacheDataExpiresAt);
    }

    /**
     * @inheritDoc
     */
    public function getDataByToken(string $token): ?string
    {
        $data = $this->redis->get(self::TOKEN_DATA_KEY . $token);

        if ($data === false) {
            return null;
        }

        return $data;
    }
}