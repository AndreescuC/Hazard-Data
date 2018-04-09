<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/index.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/login.html');
    }

    /**
     * @Route("/users", name="users")
     */
    public function usersAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/users.html');
    }

    /**
 * @Route("/providers", name="providers")
 */
    public function providersAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/providers.html');
    }
}
