<?php

namespace PaddlereBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FacilityRepository extends EntityRepository
{
    public function findAllActiveWithCredit()
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();
        return $qb->select('f.id,f.name')
                ->addSelect('sum(g.credit) as credit')
                ->from('PaddlereBundle:Facility','f')
                ->where($qb->expr()->eq('f.enabled', true))
                ->leftJoin('f.guests','g')
                ->addGroupBy('f.id')
            ->getQuery()->getArrayResult();
    }
}