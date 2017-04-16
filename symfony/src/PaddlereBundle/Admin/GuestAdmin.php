<?php

namespace PaddlereBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use PaddlereBundle\Entity\Guest;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class GuestAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $mapper)
    {
        $mapper
            ->addIdentifier('name')
            ->add('facility')
            ->add('tags')
            ->add('credit')
            ->add('fun')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('facility')
            ->add('fun')
            ->add('tags')
            ->add('enabled')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('name')
            ->add('facility')
            ->add('credit')
            ->add('fun');

		/** @var Guest $guest */
        $guest = $this->getSubject();
        if (!is_null($guest->getId()) && !is_null($guest->getFacility())) {
            /** @var QueryBuilder $qb */
            $qb = $this->getModelManager()->getEntityManager('PaddlereBundle:Tag')->createQueryBuilder('t');
            $qb->select('t')
                ->from('PaddlereBundle:Tag','t')
                ->where($qb->expr()->orX($qb->expr()->isNull('t.guest'),$qb->expr()->eq('t.guest',':guest')))
                ->andWhere($qb->expr()->eq('t.facility',':facility'))
                ->setParameters(array(
                    'guest' => $guest,
                    'facility' => $guest->getFacility()
                    ));
            $mapper
                ->add('tags', null, array('by_reference' => false, 'query_builder' => $qb));
        }

        $mapper
            ->add('enabled')
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('name')
            ->add('facility')
            ->add('credit')
            ->add('fun')
            ->add('tags')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    public function getNewInstance()
    {
        /** @var Guest $guest */
        $guest = parent::getNewInstance();
        $guest->setEnabled(true);
        $guest->setCredit(0);
        return $guest;
    }

}
