<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class RestApiController extends Controller
{

    /**
     * @Rest\Get("/api")
     */
    public function apiAction()
    {
        return new Response('<html><body>Hello</body></html>');
    }
}
