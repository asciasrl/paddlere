<?php

namespace PaddlereBundle\Admin;

use PaddlereBundle\Entity\Facility;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FacilityAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name')
            ->add('devices', null, array('associated_property' => 'name'))
            ->add('fields', null, array('associated_property' => 'name'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('devices')
            ->add('tags')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('name')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('devices', null, array('associated_property' => 'name'))
            ->add('fields', null, array('associated_property' => 'name'))
            ->add('tags', null, array('associated_property' => 'name'))
        ;
    }

}
