<?php
declare(strict_types=1);

namespace App\Repository;

use App\Cache\CacheInterface;
use App\Service\TokenGenerator;
use App\ValueObject\User;
use SocialTech\StorageInterface;

/**
 * This class is used to save and fetch user data from storage
 */
class UserRepository
{
    /** @var StorageInterface */
    private $fileStorage;

    /** @var CacheInterface */
    private $cache;

    /** @var string */
    private $storagePath;

    /** @var TokenGenerator */
    private $tokenGenerator;

    /**
     * @param StorageInterface $fileStorage
     * @param CacheInterface $cache
     * @param string $storagePath
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(
        StorageInterface $fileStorage,
        CacheInterface $cache,
        string $storagePath,
        TokenGenerator $tokenGenerator
    )
    {
        $this->fileStorage = $fileStorage;
        $this->cache = $cache;
        $this->storagePath = $storagePath;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $userId = $this->cache->getUsersAutoincrement();

        $userData[$userId] = [
            "id" => $userId,
            "nickname" => $user->getNickname(),
            "firstname" => $user->getFirstName(),
            "lastname" => $user->getLastName(),
            "password" => $user->getPassword(),
            "age" => $user->getAge()
        ];

        if ($this->fileStorage->exists($this->storagePath)) {
            $this->fileStorage->append($this->storagePath, json_encode($userData));
        } else {
            $this->fileStorage->store($this->storagePath, json_encode($userData));
        }
    }

    /**
     * @param string $field
     * @param string $value
     * @return null|User
     */
    public function findBy(string $field, string $value): ?User
    {
        $usersData = json_decode($this->fileStorage->load($this->storagePath), true);

        foreach ($usersData as $userData) {
            if ($userData[$field] === $value) {
                return $this->createUserObjectFromArray($userData);
            }
        }

        return null;
    }

    /**
     * Save user data to cache using random token as key
     * and return this token as auth key
     *
     * @param User $user
     * @return string
     */
    public function createAuthTokenForUser(User $user): string
    {
       $token = $this->tokenGenerator->generateToken();

       $this->cache->saveDataByToken($token, serialize($user));

       return $token;
    }

    /**
     * Get user data from cache using auth token as key
     *
     * @param string $apiToken
     * @return null|User
     */
    public function findByAuthToken(string $apiToken): ?User
    {
        $userData = $this->cache->getDataByToken($apiToken);

        if (is_null($userData)) {
            return null;
        }

        return unserialize($userData);
    }

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $fileStorageData = $this->fileStorage->load($this->storagePath);

        return json_decode($fileStorageData, true);
    }

    /**
     * @param array $userData
     * @return User
     */
    private function createUserObjectFromArray(array $userData): User
    {
        $user = new User();

        $user
            ->setId($userData['id'])
            ->setNickname($userData['nickname'])
            ->setPassword($userData['password'])
            ->setFirstName($userData['firstname'])
            ->setLastName($userData['lastname'])
            ->setAge($userData['age']);

        return $user;
    }
}