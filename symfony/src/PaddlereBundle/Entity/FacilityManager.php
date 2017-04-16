<?php

namespace PaddlereBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class FacilityManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Facility', $registry);
    }

    public function findAllActiveWithCredit()
    {
        return $this->getRepository()->findAllActiveWithCredit();
    }
}
