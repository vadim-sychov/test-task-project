<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\ValueObject\User;
use App\Repository\UserRepository;
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
     * @param User $user
     */
    public function __invoke(User $user)
    {
        $this->userRepository->save($user);
    }
}