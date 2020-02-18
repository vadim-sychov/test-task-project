<?php

namespace App\Security;

use App\ValueObject\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username): void
    {
        // Load a User object from your data source or throw UsernameNotFoundException.
        // The $username argument may not actually be a username:
        // it is whatever value is being returned by the getUsername()
        // method in your User class.
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user): void
    {
        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
    }

    /**
     * Tells Symfony to use this provider for this User class.
     *
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
