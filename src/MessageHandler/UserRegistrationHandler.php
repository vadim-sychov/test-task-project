<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserRegistrationMessage;
use SocialTech\StorageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserRegistrationHandler implements MessageHandlerInterface
{
    /** @var StorageInterface */
    private $jsonStorage;

    /**
     * @param StorageInterface $jsonStorage
     */
    public function __construct(StorageInterface $jsonStorage)
    {
        $this->jsonStorage = $jsonStorage;
    }

    /**
     * @param UserRegistrationMessage $userRegistrationMessage
     */
    public function __invoke(UserRegistrationMessage $userRegistrationMessage)
    {
        if ($this->jsonStorage->exists( 'storage/users.json')) {
            $this->jsonStorage->append('storage/users.json', json_encode(['nickname' => 'vadim', 'password' => '123']));
        } else {
            $this->jsonStorage->store('storage/users.json', json_encode(['nickname' => 'vadim', 'password' => '123']));
        }
    }
}