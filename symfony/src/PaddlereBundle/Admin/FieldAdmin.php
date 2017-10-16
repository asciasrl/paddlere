<?php

namespace PaddlereBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class FieldAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('timeline', $this->getRouterIdParameter() . '/timeline');
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name')
            ->add('facility')
            ->add('_action', null, array(
                'actions' => array(
                    'timeline' => array(
                        'template' => 'PaddlereBundle:CRUD:list__action_timeline.html.twig'
                    )
                )
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('facility')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('name')
            ->add('facility')
            ->add('snapshotUri')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('facility')
            ->add('snapshotUri')
            ->add('deviceField')
        ;
    }

}
