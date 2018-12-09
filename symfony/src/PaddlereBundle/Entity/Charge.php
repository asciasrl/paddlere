<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Charge
 *
 * @ORM\Table(name="charge")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Charge
{
    /**
     * @var guid
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    public function __toString()
    {
        return $this->getCreatedAt() ? $this->getAmount() . ', ' . $this->getCreatedAt()->format('c') : 'new';
    }

    /**
     * @var Guset
     * @ORM\ManyToOne(targetEntity="Guest", inversedBy="charges")
     * @ORM\JoinColumn(name="guest_id", referencedColumnName="id", nullable=false)
     */
    protected $guest;

    /**
     * @var int
     *
     * @ORM\Column(name="credit_before", type="integer")
     */
    protected $creditBefore;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string")
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Charge


    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creditBefore
     *
     * @param integer $creditBefore
     *
     * @return Charge
     */
    public function setCreditBefore($creditBefore)
    {
        $this->creditBefore = $creditBefore;

        return $this;
    }

    /**
     * Get creditBefore
     *
     * @return integer
     */
    public function getCreditBefore()
    {
        return $this->creditBefore;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Charge
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Charge
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set guest
     *
     * @param \PaddlereBundle\Entity\Guest $guest
     *
     * @return Charge
     */
    public function setGuest(\PaddlereBundle\Entity\Guest $guest)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest
     *
     * @return \PaddlereBundle\Entity\Guest
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Charge
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
