<?php

namespace PaddlereBundle\Admin;

use PaddlereBundle\Entity\Event;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EventAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->addIdentifier('datetimeFrom')
            ->add('datetimeTo')
            ->add('duration')
            ->add('field')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->add('field')
            ->add('field.device.facility')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('eventType')
            ->add('datetimeFrom')
            ->add('datetimeTo')
            ->add('field')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->add('datetimeFrom')
            ->add('datetimeTo')
            ->add('duration')
            ->add('field')
        ;
    }

    public function getNewInstance()
    {

        return parent::getNewInstance()
            ->setDatetimeFrom(new \DateTime())
            ->setDatetimeTo(new \DateTime());
        /** @var Event $event */
/*
        $event = parent::getNewInstance();

        $event->setDatetimeFrom(new \DateTime());

        return $event;
*/
    }


}
