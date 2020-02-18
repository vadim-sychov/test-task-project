<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserMessage;
use App\Repository\UserRepository;
use SocialTech\StorageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserCreateHandler implements MessageHandlerInterface
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserMessage $userMessage
     */
    public function __invoke(UserMessage $userMessage)
    {
        $this->userRepository->createFromMessage($userMessage);
    }
}