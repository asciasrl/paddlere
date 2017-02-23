<?php

namespace PaddlereBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class TagManager extends BaseEntityManager
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct('PaddlereBundle\Entity\Tag', $registry);
    }

    /**
     * Updates lastseen of the tag
     * @param Tag $tag
     */
    public function ping(Tag $tag)
    {
        $tag->setLastseenAt(new \DateTime());
        $this->getEntityManager()->flush();
    }
}
