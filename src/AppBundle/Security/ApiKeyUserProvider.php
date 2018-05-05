<?php

namespace AppBundle\Security;

use AppBundle\Service\ClientUserService;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Entity\ClientUser;

class ApiKeyUserProvider implements UserProviderInterface
{

    const LOGIN_FAIL_REASON_USERNAME = 0;
    const LOGIN_FAIL_REASON_PASSWORD = 1;
    const LOGIN_FAIL_REASON_APIKEY = 2;

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
     * @return null|string
     * @throws CustomUserMessageAuthenticationException
     */
    public function getUsernameForApiKey(string $apiKey): ?string
    {
        /** @var ClientUser $user */
        $user = $this->clientUserService->getUserByApiKey($apiKey);
        if ($user instanceof ClientUser) {
            return $user->getUsername();
        }
        throw new CustomUserMessageAuthenticationException(
            sprintf('API Key "%s" does not exist.', $apiKey),
            [],
            self::LOGIN_FAIL_REASON_APIKEY
        );
    }

    public function getUsernameForCredentials(array $credentials): ?string
    {
        /** @var ClientUser $user */
        $user = $this->clientUserService->getUserByCredentials($credentials);
        if (!$user instanceof ClientUser) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('Username "%s" does not exist.', $credentials['username']),
                [],
                self::LOGIN_FAIL_REASON_USERNAME
            );
        }
        if (password_verify($credentials['password'], $user->getPassword())) {
            return $user->getUsername();
        }

        throw new CustomUserMessageAuthenticationException(
            sprintf('Wrong password for username'),
            [],
            self::LOGIN_FAIL_REASON_PASSWORD
        );

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