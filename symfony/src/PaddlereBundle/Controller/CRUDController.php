<?php

namespace PaddlereBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use PaddlereBundle\Entity\EventManager;
use PaddlereBundle\Entity\Field;
use PaddlereBundle\Entity\Guest;
use Sonata\AdminBundle\Controller\CRUDController as SonataCRUDController;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Form;

class CRUDController extends SonataCRUDController
{

    /**
     * @param $fieldId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function timelineAction()
    {
        $request = $this->getRequest();

        /** @var Field $field */
        $field = $this->admin->getSubject();

        $data = array(
            'begin' => (new \DateTime())->sub(new \DateInterval('P1M'))->add(new \DateInterval('P1D')),
            'interval' => new \DateInterval('P31D')
        );

        /** @var Form $form */
        $form = $this->createFormBuilder()
            //->setMethod('GET')
            ->add('begin', DateType::class, array(
                'html5' => true,
                'data' => $data['begin'],
            ))
            ->add('interval', DateIntervalType::class, array(
                'placeholder' => array('days' => 'Days'),
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'data' => $data['interval'],
            ))
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        function_exists('dump') ? dump($form) : 0;

        $criteria = new Criteria();
        $criteria->where($criteria->expr()->neq('datetimeBegin', null));
        $criteria->andWhere($criteria->expr()->eq('field',$field));
        $criteria->andWhere($criteria->expr()->gte('datetimeBegin',$data['begin']));
        $criteria->andWhere($criteria->expr()->lte('datetimeBegin',(clone $data['begin'])->add($data['interval'])));
        $criteria->orderBy(array('datetimeBegin' => 'ASC'));

        /** @var EventManager $eventManager */
        $eventManager = $this->container->get('paddlere.manager.event');

        $events = $eventManager->getEntityManager()->getRepository($eventManager->getClass())->matching($criteria);

        function_exists('dump') ? dump($events->toArray()):0;

        return $this->render('PaddlereBundle:CRUD:timeline.html.twig', [
            'form' => $form->createView(),
            'field' => $field,
            'events' => $events,
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);

    }

    public function chargeAction()
    {
        /** @var Guest $guest */
        $guest = $this->admin->getSubject();
        return $this->redirectToRoute('admin_paddlere_charge_create', array('guest_id' => $guest->getId()));
    }
}