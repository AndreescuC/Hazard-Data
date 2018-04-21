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

        return new Response('Successful register', 200, ['apikey' => '']);
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
                return new Response('Successful login', 200, ['apikey' => $apikey]);
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
