<?php

namespace PaddlereBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PaddlereBundle\Entity\Guest;
use PaddlereBundle\Entity\GuestManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Form\Type\EqualType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ChargeAdmin extends AbstractAdmin
{

    public function configureRoutes(RouteCollection $collection)  {
        $collection->clearExcept(array('create', 'list', 'show', 'export'));
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
            ->addIdentifier('createdAt')
            ->add('guest')
            ->add('amount')
            ->add('createdBy')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        $mapper
            ->add('guest')
            ->add('amount')
            ->add('createdBy')
        ;
    }

	protected function configureFormFields(FormMapper $mapper)
	{
		$mapper
            ->add('guest', EntityType::class, ['class' => Guest::class,
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('g');
                    return $qb
                        ->where($qb->expr()->eq('g.enabled', true));
                }])
            ->add('amount')
            ->add('createdBy', null, ['attr' => ['readonly' => true]])
		;
	}

    protected function configureShowFields(ShowMapper $mapper)
    {
        $mapper
            ->add('createdAt')
            ->add('guest')
            ->add('creditBefore')
            ->add('amount')
            ->add('createdBy')
        ;
    }

    public function getNewInstance()
    {
        $charge = parent::getNewInstance();
        $charge->setAmount(1000);
        $charge->setCreatedBy($_SERVER['PHP_AUTH_USER']);
        $guestId = $this->getRequest()->query->get('guest_id');
        if (!empty($guestId)) {
            $guestManager = $this->getConfigurationPool()->getContainer()->get('paddlere.manager.guest');
            /** @var Guest $guest */
            $guest = $guestManager->getEntityManager()->getRepository($guestManager->getClass())->find($guestId);
            if ($guest) {
                if ($guest->getEnabled()) {
                    $charge->setGuest($guest);
                } else {
                    $this->getConfigurationPool()->getContainer()->get('session')->getFlashBag()->add('error', $guest . ' not enabled!');
                }
            } else {
                $this->getConfigurationPool()->getContainer()->get('session')->getFlashBag()->add('error', 'Guest not found');
            }
        }
        return $charge;
    }

    public function preValidate($charge)
    {
        $charge->setCreditBefore($charge->getGuest()->getCredit());
        parent::preValidate($charge);
    }

    public function prePersist($charge)
    {
        $charge->getGuest()->setCredit($charge->getGuest()->getCredit() + $charge->getAmount());
        parent::prePersist($charge);
    }
}
