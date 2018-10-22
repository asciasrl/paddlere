<?php

namespace PaddlereBundle\Admin;

use PaddlereBundle\Entity\Facility;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Form\Type\EqualType;

class FacilityAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        'enabled' => [
            'type'  => EqualType::TYPE_IS_EQUAL, // => 1
            'value' => BooleanType::TYPE_YES     // => 1
        ]
    ];

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name')
            ->add('devices', null, array('associated_property' => 'name'))
            ->add('fields', null, array('associated_property' => 'name'))
            ->add('enabled')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('devices')
            ->add('tags')
            ->add('enabled')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('name')
            ->add('enabled')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('devices', null, array('associated_property' => 'name'))
            ->add('fields', null, array('associated_property' => 'name'))
            ->add('tags', null, array('associated_property' => 'serial'))
            ->add('guests', null, array('associated_property' => 'name'))
            ->add('hosts', null, array('associated_property' => 'name'))
            ->add('enabled')
        ;
    }

}
