<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ClientUser;
use AppBundle\Entity\DataProvider;
use AppBundle\Entity\Parameters;
use AppBundle\Service\ClientUserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        /** @var ClientUserService $userClientService */
        $statisticsService = $this->get('app.service.statistics_service');

        return $this->render('niceAdminBootstrap/index.twig', [
            'statistics' => $statisticsService->getDashboardStatistics()
        ]);
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
        $users = $this->getDoctrine()->getRepository(ClientUser::class)
            ->findAll();
        return $this->render('niceAdminBootstrap/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/ajax-user", name="ajax-user")
     */
    public function usersAjaxAction(Request $request)
    {
        $username = $request->request->get('username');
        $userTrustLevel = $request->request->get('trust_level');
        $userStatus = $request->request->get('status');
        if (!isset($username, $userTrustLevel, $userStatus)) {
            return new JsonResponse(['status' => -1]);
        }
        if ($userTrustLevel > 100 || $userTrustLevel < 0) {
            return new JsonResponse(['status' => -1]);
        }

        $user = $this->getDoctrine()->getRepository(ClientUser::class)->findOneBy(['username' => $username]);
        if (!$userTrustLevel instanceof ClientUser) {
            return new JsonResponse(['status' => -1]);
        }
        $user->setTrustLevel($userTrustLevel);
        $user->setStatus($userStatus);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['status' => 0]);
    }

    /**
     * @Route("/ajax-provider", name="ajax-provider")
     */
    public function providersAjaxAction(Request $request)
    {
        $code = $request->request->get('code');
        $providerStatus = $request->request->get('status');
        if (!isset($code, $providerStatus)) {
            return new JsonResponse(['status' => -1]);
        }

        $provider = $this->getDoctrine()->getRepository(DataProvider::class)->findOneBy(['code' => $code]);
        if (!$provider instanceof DataProvider) {
            return new JsonResponse(['status' => -1]);
        }
        $provider->setStatus($providerStatus);
        $em = $this->getDoctrine()->getManager();
        $em->persist($provider);
        $em->flush();

        return new JsonResponse(['status' => 0]);
    }

    /**
     * @Route("/providers", name="providers")
     */
    public function providersAction(Request $request)
    {
        $providers = $this->getDoctrine()->getRepository(DataProvider::class)
            ->findAll();
        return $this->render('niceAdminBootstrap/providers.html.twig', [
            'providers' => $providers
        ]);
    }

    /**
     * @Route("/params", name="params")
     */
    public function paramasAction(Request $request)
    {
        $applicationParameters = $this->getDoctrine()->getRepository(Parameters::class)
            ->findOneBy(['id' => 1]);

        $form = $this->createFormBuilder($applicationParameters)
            ->add('warningRadius', NumberType::class)
            ->add('warningTolerance', NumberType::class)
            ->add('apiKeyGenerationMinValue', NumberType::class)
            ->add('apiKeyGenerationMaxValue', NumberType::class)
            ->add('apiKeyGenerationMaxAttempts', NumberType::class)
            ->add('firabaseServerKey', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();


        return $this->render('niceAdminBootstrap/params.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
