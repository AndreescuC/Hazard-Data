<?php

/**
 * Created by PhpStorm.
 * User: constantin.andreescu
 * Date: 4/10/2018
 * Time: 1:50 PM
 */
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Entity\ClientUser;

class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @param string $apiKey
     * @return string
     */
    public function getUsernameForApiKey($apiKey): string
    {
        //TODO: query for username by apiKey
        $username = 'John';

        return $username;
    }

    /**
     * @param string $username
     * @return ClientUser
     */
    public function loadUserByUsername($username): ClientUser
    {
        //TODO: query for user by username
        $user = new ClientUser($username, null, null);
        return $user;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return ClientUser::class === $class;
    }

    public function refreshUser(UserInterface $user)
    {
        // stateless authentication
        throw new UnsupportedUserException();
    }
}