<?php

namespace PaddlereBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class GuestManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Guest', $registry);
    }

    /**
     * Updates lastseen of the guest
     * @param Guest $guest
     */
    public function ping(Guest $guest)
    {
        $guest->setLastseenAt(new \DateTime());
        $this->getEntityManager()->flush();
    }

}
