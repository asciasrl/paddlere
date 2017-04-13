<?php

namespace PaddlereBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class TagAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('serial')
            ->add('facility')
            ->add('guest')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('serial')
            ->add('facility')
            ->add('guest')
            ->add('enabled')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('serial')
            ->add('facility')
            ->add('guest')
            ->add('enabled')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('serial')
            ->add('facility')
            ->add('guest')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

}
