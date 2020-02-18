<?php
declare(strict_types=1);

namespace App\Repository;

use App\Cache\CacheInterface;
use App\Message\UserMessage;
use SocialTech\StorageInterface;

/**
 * This class is used to store and fetch user data from storage
 */
class UserRepository
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
     * @param UserMessage $userMessage
     */
    public function createFromMessage(UserMessage $userMessage): void
    {
        $userId = $this->cache->getUsersAutoincrement();

        $userData[$userId] = [
            "id" => $userId,
            "nickname" => $userMessage->getNickname(),
            "firstname" => $userMessage->getFirstName(),
            "lastname" => $userMessage->getLastName(),
            "password" => $userMessage->getPassword(),
            "age" => $userMessage->getAge()
        ];

        //TODO хешировать пароль когда сделаю систему аутентификации

        if ($this->fileStorage->exists($this->storagePath)) {
            $this->fileStorage->append($this->storagePath, json_encode($userData));
        } else {
            $this->fileStorage->store($this->storagePath, json_encode($userData));
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