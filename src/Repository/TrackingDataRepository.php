<?php
declare(strict_types=1);

namespace App\Repository;

use App\Cache\CacheInterface;
use App\ValueObject\TrackingData;
use SocialTech\StorageInterface;

/**
 * This class is used to save and fetch tracking-data from storage
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
     * @param TrackingData $trackingData
     */
    public function save(TrackingData $trackingData): void
    {
        $trackingDataId = $this->cache->getTrackingDataAutoincrement();

        $storageData[$trackingDataId] = [
            "id" => $trackingDataId,
            "id_user" => $trackingData->getUserId(),
            "source_label" => $trackingData->getSourceLabel(),
            "date_created" => $trackingData->getCreatedDate()->format('Y-m-d H:i:s')
        ];

        if ($this->fileStorage->exists($this->storagePath)) {
            $this->fileStorage->append($this->storagePath, json_encode($storageData));
        } else {
            $this->fileStorage->store($this->storagePath, json_encode($storageData));
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