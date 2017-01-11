<?php

namespace Ascia\GoogleCalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AsciaGoogleCalendarBundle:Default:index.html.twig');
    }
}
