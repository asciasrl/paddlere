<?php

namespace PaddlereBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class TransactionManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Transaction', $registry);
    }

    /**
     * Save transaction and update guest credit
     *
     * @param object $entity
     * @param bool $andFlush
     */
    public function save($entity, $andFlush = true)
    {
        parent::save($entity,false);

        /** @var Transaction $transaction */
        $transaction = $entity;
        $guest = $transaction->getGuest();
        if ($transaction->getAmount() != 0 && !empty($guest)) {
            $guest->setCredit($guest->getCredit() - $transaction->getAmount());
            $this->getObjectManager()->persist($guest,false);
        }

        if ($andFlush) {
            $this->getObjectManager()->flush();
        }
    }

}
