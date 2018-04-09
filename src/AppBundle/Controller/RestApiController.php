<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class RestApiController extends FOSRestController
{

    /**
     * @Rest\Post("/api-register")
     */
    public function registerAction()
    {
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
