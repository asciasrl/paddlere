<?php

namespace PaddlereBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class HostAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name')
            ->add('facility')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('facility')
            ->add('enabled')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('name')
            ->add('facility', null, array('required' => true, 'placeholder' => '---'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'mapped' => false,
                'required' => false,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Password confirmation'),
                'invalid_message' => 'Password mismatch',
            ))
            ->add('enabled')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('facility')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    public function getNewInstance()
    {
        return parent::getNewInstance()->setEnabled(true);
    }

    public function preUpdate($object)
    {
        $plainPassword = $this->getForm()->get('plainPassword')->getNormData();
        if (!empty($plainPassword)) {
            $object->setPassword($plainPassword);
        }
    }

}
