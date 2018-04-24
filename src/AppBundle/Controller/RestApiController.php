<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientUser;
use AppBundle\Service\ClientUserService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestApiController extends FOSRestController
{

    /**
     * @Rest\Get("/api-register")
     */
    public function registerAction(Request $request)
    {
        $username = $request->get('username');
        $pass = $request->get('password');
        if (!is_string($username) || !is_string($pass)) {
            return new Response(
                'Register failed, missing credentials',
                200,
                ['reason' => 'missing_credentials']
            );
        }

        /** @var ClientUserService $clientUserService */
        $clientUserService = $this->get('app.service.client_user_service');
        if (!$clientUserService->validateRegisterRequest($username)) {
            return new Response(
                'Register failed, credentials already present',
                200,
                ['reason' => 'duplicate_credentials']
            );
        }
        $userData = [];
        foreach (['username', 'password', 'first_name', 'last_name', 'email', 'home_town'] as $item) {
            $userData[$item] = $request->get($item);
        }
        if ($apiKey = $clientUserService->saveNewUser($userData)) {
            return new Response(
                'Register failed, we encountered some technical issues, we\'re working on it',
                500,
                ['reason' => 'internal_server_error']
            );
        }
        return new Response('Successful register', 200, ['apikey' => $apiKey]);

    }

    /**
     * @Rest\Get("/api-login")
     */
    public function loginAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user instanceof ClientUser) {
            /** @var ClientUserService $clientUserService */
            $clientUserService = $this->get('app.service.client_user_service');
            try {
                $apikey = $user->getUserAPIKey() === NULL ? $clientUserService->refreshApiKeyForUser($user) : NULL;

                $response = new Response();
                $response->headers->set('apikey', $apikey);

                return $response->setContent('Successful login')->setStatusCode(200);

            } catch (\ApiKeyGenerationException $e) {

                $this->get('doctrine')->getManager()->detach($user);
                return new Response($e->getMessage(), 500);
            }
        }

        return new Response('I swear we\'re working on it!!', 500);
    }

    /**
     * @Rest\Post("/api-warning")
     */
    public function warningAction()
    {
    }

    /**
     * @Rest\Post("/api-feedback")
     */
    public function feedbackAction()
    {
    }
}
