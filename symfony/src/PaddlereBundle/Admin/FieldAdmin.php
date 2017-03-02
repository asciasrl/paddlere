<?php

namespace PaddlereBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FieldAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name')
            ->add('device')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('device')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('name')
            ->add('device')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('device')
            ->add('device.facility')
        ;
    }

}
