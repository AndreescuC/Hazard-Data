<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientUser;
use AppBundle\Service\ClientUserService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestApiController extends FOSRestController
{

    const STATUS_SUCCESFULL = 0;
    const STATUS_FAILED = 1;

    const REASON_DUPLICATE_USERNAME = 0;
    const REASON_INTERNAL_ERROR = 1000;
    const REASON_BAD_REQUEST = 1001;

    /**
     * @Rest\Post("/api-register")
     */
    public function registerAction(Request $request)
    {
        $username = $request->get('username');
        $pass = $request->get('password');
        if (!is_string($username) || !is_string($pass)) {
            return new JsonResponse([
                'status' => self::STATUS_FAILED,
                'reason' => self::REASON_BAD_REQUEST
            ]);
        }

        /** @var ClientUserService $clientUserService */
        $clientUserService = $this->get('app.service.client_user_service');
        if (!$clientUserService->validateRegisterRequest($username)) {
            return new JsonResponse([
                'status' => self::STATUS_FAILED,
                'reason' => self::REASON_DUPLICATE_USERNAME
            ]);
        }
        $userData = [];
        foreach (['username', 'password', 'first_name', 'last_name', 'email', 'home_town'] as $item) {
            $userData[$item] = $request->get($item);
        }
        if (!$apiKey = $clientUserService->saveNewUser($userData)) {
            return new JsonResponse([
                'status' => self::STATUS_FAILED,
                'reason' => self::REASON_INTERNAL_ERROR
            ]);
        }
        return new JsonResponse([
            'status' => self::STATUS_SUCCESFULL,
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
                $apikey = $user->getUserAPIKey() === NULL
                    ? $clientUserService->refreshApiKeyForUser($user)
                    : NULL;

                $responseData = ['status' => self::STATUS_SUCCESFULL];
                if ($apikey) {
                    $responseData['apikey'] = $apikey;
                }
                return new JsonResponse($responseData);

            } catch (\ApiKeyGenerationException $e) {
                $this->get('doctrine')->getManager()->detach($user);
                return new JsonResponse(['status' => self::STATUS_FAILED, 'reason' => self::REASON_INTERNAL_ERROR]);
            }
        }
        return new JsonResponse(['status' => self::STATUS_FAILED, 'reason' => self::REASON_INTERNAL_ERROR]);
    }

    /**
     * @Rest\Post("/api-warning")
     */
    public function warningAction(Request $request)
    {
        $apikey = $request->headers->get('apikey');
        $lat = $request->get('lat');
        $long = $request->get('long');

        if (!isset($apikey, $lat, $long)) {
            return new JsonResponse([
                'status' => self::STATUS_FAILED,
                'reason' => self::REASON_BAD_REQUEST
            ]);
        }

        $data = [
            'sender_apikey' => $apikey,
            'ext_id' => $request->get('id'),
            'hazard' => [
                'type' => $request->get('hazard'),
                'population' => $request->get('population'),
                'loc' => [
                    'lat' => $lat,
                    'long' => $long
                ],
                'gravity' => $request->get('gravity')
            ]
        ];

        $response = $this->get('app.service.warning_service')->handleIncomingWarning($data)
            ? ['status' => self::STATUS_SUCCESFULL]
            : ['status' => self::STATUS_FAILED, 'reason' => self::REASON_INTERNAL_ERROR];
        return new JsonResponse($response);
    }
}
