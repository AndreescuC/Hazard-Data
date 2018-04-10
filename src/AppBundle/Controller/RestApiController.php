<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestApiController extends FOSRestController
{

    /**
     * @Rest\Post("/api-register")
     */
    public function registerAction(Request $request)
    {
        if ($request->get('body')) {
            $response = $request->get('body');
        } else {
            $response = "No param";
        }
        return new JsonResponse($response);
    }

    /**
     * @Rest\Put("/api-login")
     */
    public function loginAction()
    {
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
