<?php

namespace PaddlereBundle\Admin;

use PaddlereBundle\Entity\Event;
use PaddlereBundle\PaddlereBundle;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EventAdmin extends AbstractAdmin
{

    protected $datagridValues = array(
        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'datetimeBegin',
    );

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->addIdentifier('datetimeBegin')
            ->add('datetimeEnd')
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
            ->add('datetimeBegin', 'sonata_type_datetime_picker', array(
                'format'                => 'Y-MM-dd H:mm:ss',
                'dp_side_by_side'       => true,
            ))
            ->add('datetimeEnd', 'sonata_type_datetime_picker', array(
                'format'                => 'Y-MM-dd H:mm:ss',
                'dp_side_by_side'       => true,
            ))
            ->add('field')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->add('datetimeBegin', null, array('format' => 'r'))
            ->add('datetimeEnd', null, array('format' => 'r'))
            ->add('duration')
            ->add('device')
            ->add('field')
            ->add('tag')
        ;
    }

    public function getNewInstance()
    {

        return parent::getNewInstance()
            ->setDatetimeBegin(new \DateTime())
            ->setDatetimeEnd(new \DateTime());
        /** @var Event $event */
/*
        $event = parent::getNewInstance();

        $event->setDatetimeBegin(new \DateTime());

        return $event;
*/
    }


}
