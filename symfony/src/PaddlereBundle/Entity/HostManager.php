<?php

namespace PaddlereBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class HostManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Host', $registry);
    }

    /**
     * Updates lastseen of the host
     * @param Host $host
     */
    public function ping(Host $host)
    {
        $host->setLastseenAt(new \DateTime());
        $this->getEntityManager()->flush();
    }

}
