<?php
declare(strict_types=1);

namespace App\Repository;

use App\Cache\CacheInterface;
use App\Message\TrackingDataMessage;
use SocialTech\StorageInterface;

/**
 * This class is used to store and fetch tracking-data from storage
 */
class TrackingDataRepository
{
    /** @var StorageInterface */
    private $fileStorage;

    /** @var CacheInterface */
    private $cache;

    /** @var string */
    private $storagePath;

    /**
     * @param StorageInterface $fileStorage
     * @param CacheInterface $cache
     * @param string $storagePath
     */
    public function __construct(StorageInterface $fileStorage, CacheInterface $cache, string $storagePath)
    {
        $this->fileStorage = $fileStorage;
        $this->cache = $cache;
        $this->storagePath = $storagePath;
    }

    /**
     * @param TrackingDataMessage $trackingDataMessage
     */
    public function createFromMessage(TrackingDataMessage $trackingDataMessage): void
    {
        $trackingDataId = $this->cache->getTrackingDataAutoincrement();

        $trackingData[$trackingDataId] = [
            "id" => $trackingDataId,
            "id_user" => $trackingDataMessage->getUserId(),
            "source_label" => $trackingDataMessage->getSourceLabel(),
            "date_created" => $trackingDataMessage->getCreatedDate()->format('Y-m-d H:i:s')
        ];

        if ($this->fileStorage->exists($this->storagePath)) {
            $this->fileStorage->append($this->storagePath, json_encode($trackingData));
        } else {
            $this->fileStorage->store($this->storagePath, json_encode($trackingData));
        }
    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $fileStorageData = $this->fileStorage->load($this->storagePath);

        return json_decode($fileStorageData, true);
    }
}