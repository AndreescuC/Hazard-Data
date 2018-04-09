<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use UserRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/index.twig', array('activeUsers' => $activeUsers));
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

    /**
     * @Route("/add_new-entity", name="new_entity")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $user->setFirstName("qwefew");
        $user->setLastName("wqeqw");

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->persist($user);
        $em->flush();

        return new Response("New User added");

        //return $this->render('niceAdminBootstrap/providers.html');
    }

}
