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

}
