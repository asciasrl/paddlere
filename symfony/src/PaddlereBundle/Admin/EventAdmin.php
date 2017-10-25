<?php

namespace PaddlereBundle\Admin;

use Exporter\Source\DoctrineORMQuerySourceIterator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class EventAdmin extends AbstractAdmin
{

    protected $datagridValues = array(
        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'createdAt',
    );

    public function configureRoutes(RouteCollection $collection)  {
        $collection->clearExcept(array('list', 'show'));
    }

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->addIdentifier('datetimeBegin')
            ->add('datetimeEnd')
            ->add('duration')
            ->add('field.facility', null, array('label' => 'Facility', 'associated_property' => 'name'))
            ->add('field', null, array('associated_property' => 'name'))
            ->add('tag')
            ->add('guest', null, array('associated_property' => 'name'))
            ->add('host', null, array('associated_property' => 'name'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('eventType')
            ->add('field.facility')
            ->add('field')
            ->add('tag')
            ->add('guest')
            ->add('host')
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
            ->add('createdAt', null, array('format' => 'r'))
            ->add('updatedAt', null, array('format' => 'r'))
            ->add('eventType')
            ->add('datetimeBegin', null, array('format' => 'r'))
            ->add('datetimeEnd', null, array('format' => 'r'))
            ->add('duration')
            ->add('device')
            ->add('field')
            ->add('tag')
            ->add('guest')
            ->add('host')
            ->add('snapshot', null, ['template' => 'PaddlereBundle:CRUD:show_image.html.twig'])
            ->add('transactions')
        ;
    }

    public function getNewInstance()
    {
        return parent::getNewInstance()
            ->setDatetimeBegin(new \DateTime())
            ->setDatetimeEnd(new \DateTime());
    }

    public function getExportFields()
    {
        return array('field', 'eventType', 'datetimeBegin', 'datetimeEnd', 'duration', 'guest', 'host', 'createdAt', 'updatedAt');
    }

    public function getExportFormats()
    {
        return array('xls');
    }

    public function getDataSourceIterator()
    {
        /** @var DoctrineORMQuerySourceIterator $iterator */
        $iterator = parent::getDataSourceIterator();
        $iterator->setDateTimeFormat('Y-m-d\TH.i.s');
        return $iterator;
    }

}
