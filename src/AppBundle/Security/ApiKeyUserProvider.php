<?php

namespace AppBundle\Security;

use AppBundle\Service\ClientUserService;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Entity\ClientUser;

class ApiKeyUserProvider implements UserProviderInterface
{

    /** @var  ClientUserService $clientUserService*/
    private $clientUserService;

    /**
     * @param ClientUserService $clientUserService
     */
    public function setClientUserService(ClientUserService $clientUserService)
    {
        $this->clientUserService = $clientUserService;
    }

    /**
     * @param string $apiKey
     * @return string|null
     */
    public function getUsernameForApiKey(string $apiKey): ?string
    {
        /** @var ClientUser $user */
        $user = $this->clientUserService->getUserByApiKey($apiKey);
        return $user instanceof ClientUser ? $user->getUsername() : null;
    }

    /**
     * @param string $username
     * @return ClientUser
     */
    public function loadUserByUsername($username): ClientUser
    {
        $user = $this->clientUserService->getUserByUsername($username);
        if (!$user instanceof ClientUser) {
            throw new UsernameNotFoundException("No user found for username " . $username);
        }
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