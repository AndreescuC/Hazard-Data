<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientUser;
use AppBundle\Service\ClientUserService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestApiController extends FOSRestController
{

    /**
     * @Rest\Post("/api-register")
     */
    public function registerAction(Request $request)
    {
        $username = $request->get('username');
        $pass = $request->get('password');
        if (!is_string($username) || !is_string($pass)) {
            return new JsonResponse([
                'status' => 1,
                'reason' => 1000
            ]);
        }

        /** @var ClientUserService $clientUserService */
        $clientUserService = $this->get('app.service.client_user_service');
        if (!$clientUserService->validateRegisterRequest($username)) {
            return new JsonResponse([
                'status' => 1,
                'reason' => 0
            ]);
        }
        $userData = [];
        foreach (['username', 'password', 'first_name', 'last_name', 'email', 'home_town'] as $item) {
            $userData[$item] = $request->get($item);
        }
        if (!$apiKey = $clientUserService->saveNewUser($userData)) {
            return new JsonResponse([
                'status' => 1,
                'reason' => 0
            ]);
        }
        return new JsonResponse([
            'status' => 0,
            'apikey' => $apiKey
        ]);

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
                $responseData = ['status' => 0];
                if ($apikey) {
                    $responseData['apikey'] = $apikey;
                }
                return new JsonResponse($responseData);

            } catch (\ApiKeyGenerationException $e) {
                $this->get('doctrine')->getManager()->detach($user);
                return new JsonResponse(['status' => 1, 'reason' => 1000]);
            }
        }
        return new JsonResponse(['status' => 1, 'reason' => 1000]);
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
