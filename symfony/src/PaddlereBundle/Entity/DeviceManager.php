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

    /**
     * Updates lastseen of the device
     * @param Device $device
     * @param \DateTime $dateTime default now
     */
    public function ping(Device $device, \DateTime $dateTime=null)
    {
        if ($dateTime === null) {
            $dateTime = new \DateTime();
        }
        if ($dateTime > $device->getLastseenAt()) {
            $device->setLastseenAt($dateTime);
            $this->getEntityManager()->flush();
        }
    }
}
