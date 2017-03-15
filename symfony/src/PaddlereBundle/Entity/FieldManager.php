<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class FieldManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Field', $registry);
    }

    /**
     * @param Device $device
     * @param int $num
     */
    public function findOneByDeviceField(Device $device,$num)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('f')
            ->from('PaddlereBundle:DeviceField','df')
            ->innerJoin('PaddlereBundle:Field', 'f', Join::WITH, 'df.field = f')
            ->where($qb->expr()->eq('df.device',':device'))
            ->andWhere($qb->expr()->eq('df.num',$num))
            ->setParameter('device', $device)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

}
