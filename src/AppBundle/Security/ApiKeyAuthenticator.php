<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{

    public function createToken(Request $request, $providerKey)
    {
        $credentials = [
            'apikey' => $request->headers->get('apikey'),
            'username' => $request->query->get('username'),
            'password' => $request->query->get('password')
        ];

        // either apikey or username and pass are provided
        if (!$credentials['apikey'] && !($credentials['username'] && $credentials['password'])) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'anon.',
            $credentials,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }



    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $credentials = $token->getCredentials();
        $apiKey = $credentials['apikey'];
        $username = $credentials['apikey'];
        $password = $credentials['apikey'];

        $username = empty($username) || empty($password)
             ? $userProvider->getUsernameForApiKey($apiKey)
             : $userProvider->getUsernameForCredentials($credentials);

        if (!$username) {
            // feedback to the client
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiKey)
            );
        }
        $user = $userProvider->loadUserByUsername($username);

        if (!$apiKey) {
            $user->eraseApiKey();
        }

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }
}