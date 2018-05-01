<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DevComponentsController extends Controller
{

    /**
     * @Route("/chart-chartjs.html", name="chart")
     */
    public function chartAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/chart-chartjs.html');
    }

    /**
     * @Route("/form_component.html", name="form_component")
     */
    public function form_componentAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/form_component.html');
    }

    /**
     * @Route("/form_validation.html", name="form_validation")
     */
    public function form_validationAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/form_validation.html');
    }

    /**
     * @Route("/general.html", name="general")
     */
    public function generalAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/general.html');
    }

    /**
     * @Route("/buttons.html", name="buttons")
     */
    public function buttonsAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/buttons.html');
    }

    /**
     * @Route("/grids.html", name="grids")
     */
    public function gridsAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/grids.html');
    }

    /**
     * @Route("/basic_table.html", name="basic_table")
     */
    public function basic_tableAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/basic_table.html');
    }

    /**
     * @Route("/contact.html", name="contact")
     */
    public function contactAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/contact.html.twig');
    }

    /**
     * @Route("/blank.html", name="blank")
     */
    public function blankAction(Request $request)
    {
        return $this->render('niceAdminBootstrap/dev/blank.html');
    }
}
