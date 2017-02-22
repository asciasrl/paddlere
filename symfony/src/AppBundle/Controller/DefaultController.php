<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            //->setMethod('GET')
            ->add('begin', DateType::class, array(
                'html5' => true,
                'data' => (new \DateTime())->sub(new \DateInterval('P1M'))->add(new \DateInterval('P1D'))
            ))
            ->add('interval', DateIntervalType::class, array(
                'placeholder' => array('days' => 'Days'),
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'data' => new \DateInterval('P31D'),
            ))
            ->add('field', ChoiceType::class, array(
                'choices' => array('campo 1' => 'campo 1','campo 2' => 'campo 2','campo 3' => 'campo 3')
            ))
            ->add('dummy', CheckboxType::class, array('required' => false))
            ->add('Show', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            function_exists('dump') ? dump($data):0;

            $criteria = new Criteria();
            $criteria->where($criteria->expr()->neq('inizio', null));
            if ($data['dummy']) {
                $criteria->andWhere($criteria->expr()->orX(
                    $criteria->expr()->eq('campo',$data['field']),
                    $criteria->expr()->eq('evento','Dummy')));
            } else {
                $criteria->andWhere($criteria->expr()->eq('campo',$data['field']));
            }
            $criteria->andWhere($criteria->expr()->gte('dataora',$data['begin']));
            $criteria->andWhere($criteria->expr()->lte('dataora',(clone $data['begin'])->add($data['interval'])));
            $criteria->orderBy(array('inizio' => 'ASC'));

            $events = $this->getDoctrine()->getRepository('AppBundle:BorghesianaLog')->matching($criteria);

            function_exists('dump') ? dump($events):0;

        } else {
            $events = array();
        }

        return $this->render('default/timeline.html.twig', [
            'form' => $form->createView(),
            'events' => $events,
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @param Request $request
     * @Route("slimline", name="slimline")
     */
    public function slimlineAction(Request $request)
    {
        $deviceId = $request->query->getInt('D');
        if (empty($deviceId)) {
            throw new \InvalidArgumentException("DeviceID not given");
        }
        return $this->render('default/slimline.twig', [
            'DeviceId' => $deviceId,
        ]);
    }
}
