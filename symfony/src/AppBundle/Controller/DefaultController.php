<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @param Request $request
     * @Route("timeline", name="timeline")
     */
    public function timelineAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('begin', DateType::class, array(
                'html5' => true,
                'data' => (new \DateTime())->sub(new \DateInterval('P1M'))
            ))
            ->add('interval', DateIntervalType::class, array(
                'placeholder' => array('days' => 'Days'),
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'data' => new \DateInterval('P31D'),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            dump($data);

            // ... perform some action, such as saving the data to the database

        }

        return $this->render('default/timeline.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
