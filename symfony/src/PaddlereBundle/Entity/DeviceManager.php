<?php

namespace PaddlereBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class DeviceManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Device', $registry);
    }

}
