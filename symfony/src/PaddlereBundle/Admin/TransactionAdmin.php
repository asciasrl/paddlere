<?php

namespace PaddlereBundle\Admin;

use PaddlereBundle\Entity\Facility;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class TransactionAdmin extends AbstractAdmin
{
    public function configureRoutes(RouteCollection $collection)  {
        $collection->remove('edit');
        $collection->remove('create');
    }

    protected $datagridValues = array(
        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'createdAt',
    );

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('facility', null, array('associated_property' => 'name'))
            ->add('device', null, array('associated_property' => 'name'))
            ->add('field', null, array('associated_property' => 'name'))
            ->add('event', null, array('route' => array('name' => 'show')))
            ->add('host', null, array('associated_property' => 'name'))
            ->add('guest', null, array('associated_property' => 'name'))
            ->add('amount')
            ->add('accountedAt')
            ->add('createdAt')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('facility')
            ->add('field')
            ->add('guest')
            ->add('accountedAt')
        ;
    }

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('facility', null, array('associated_property' => 'name'))
            ->add('device', null, array('associated_property' => 'name'))
            ->add('field', null, array('associated_property' => 'name'))
            ->add('host', null, array('associated_property' => 'name'))
            ->add('guest', null, array('associated_property' => 'name'))
            ->add('amount')
            ->add('accountedAt')
            ->add('createdAt')
        ;
    }

}
