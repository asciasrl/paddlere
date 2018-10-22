<?php

namespace PaddlereBundle\Admin;

use PaddlereBundle\Entity\Tag;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Form\Type\EqualType;

class TagAdmin extends AbstractAdmin
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
            ->addIdentifier('serial')
            ->add('description')
            ->add('facility')
            ->add('guest')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            //->add('enabled', null, ['show_filter' => false])
            ->add('enabled')
            ->add('serial')
            ->add('description')
            ->add('facility')
            ->add('guest')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('serial')
            ->add('description')
            ->add('facility')
		;

		/** @var Tag $tag */
        $tag = $this->getSubject();
        if (!is_null($tag->getId()) && !is_null($tag->getFacility())) {
            /** @var QueryBuilder $qb */
            $qb = $this->getModelManager()->getEntityManager('PaddlereBundle:Guest')->createQueryBuilder('g');
            $qb->select('g')
                ->from('PaddlereBundle:Guest','g')
                ->where($qb->expr()->eq('g.facility',':facility'))
                ->setParameters(array(
                    'facility' => $tag->getFacility()
                ));
            $mapper
                ->add('guest', null, array('query_builder' => $qb));
        }

        $mapper
            ->add('enabled')
        ;

    }

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('serial')
            ->add('description')
            ->add('facility')
            ->add('guest')
            ->add('enabled')
            ->add('lastseenAt')
        ;
    }

    public function getNewInstance()
    {
        /** @var Tag $tag */
        $tag = parent::getNewInstance();
        $tag->setEnabled(true);
        return $tag;
    }


}
