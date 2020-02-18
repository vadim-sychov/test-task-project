<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserMessage;
use SocialTech\StorageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserCreateHandler implements MessageHandlerInterface
{
    /** @var StorageInterface */
    private $jsonStorage;

    /** @var string */
    private $storagePath;

    /**
     * @param StorageInterface $jsonStorage
     * @param string $storagePath
     */
    public function __construct(StorageInterface $jsonStorage, string $storagePath)
    {
        $this->jsonStorage = $jsonStorage;
        $this->storagePath = $storagePath;
    }

    /**
     * @param UserMessage $user
     */
    public function __invoke(UserMessage $user)
    {
        $userData[$user->getNickname()] = [
            "firstname" => $user->getFirstName(),
            "lastname" => $user->getLastName(),
            "password" => $user->getPassword(),
            "age" => $user->getAge()
        ];

        //TODO генерировать уникальный ID через автоинкремент
        //TODO хешировать пароль когда сделаю систему аутентификации

        if ($this->jsonStorage->exists($this->storagePath)) {
            $this->jsonStorage->append($this->storagePath, json_encode($userData));
        } else {
            $this->jsonStorage->store($this->storagePath, json_encode($userData));
        }
    }
}