<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PaddlereBundle\Admin\FieldAdmin;

/**
 * Device
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity
 */
class Transaction
{
    /**
     * @var guid
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility")
     */
    protected $facility;

    /**
     * @var Device
     * @ORM\ManyToOne(targetEntity="Device")
     */
    protected $device;

    /**
     * @var Field
     * @ORM\ManyToOne(targetEntity="Field")
     */
    protected $field;

    /**
     * @var Host
     * @ORM\ManyToOne(targetEntity="Host")
     */
    protected $host;

    /**
     * @var Guest
     * @ORM\ManyToOne(targetEntity="Guest")
     */
    protected $guest;

    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="Event",cascade={"persist"}, inversedBy="transactions")
     */
    protected $event;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    protected $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="accounted_at", type="datetime", nullable=true)
     */
    protected $accountedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    public function __toString()
    {
        return $this->getAccountedAt()->format('c') . ' | ' . $this->getGuest() . ' | ' . $this->getAmount();
    }

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Transaction

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
     * Set amount
     *
     * @param integer $amount
     *
     * @return Transaction
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
     * Set accountedAt
     *
     * @param \DateTime $accountedAt
     *
     * @return Transaction
     */
    public function setAccountedAt($accountedAt)
    {
        $this->accountedAt = $accountedAt;

        return $this;
    }

    /**
     * Get accountedAt
     *
     * @return \DateTime
     */
    public function getAccountedAt()
    {
        return $this->accountedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Transaction
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
     * Set facility
     *
     * @param \PaddlereBundle\Entity\Facility $facility
     *
     * @return Transaction
     */
    public function setFacility(\PaddlereBundle\Entity\Facility $facility = null)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return \PaddlereBundle\Entity\Facility
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Set device
     *
     * @param \PaddlereBundle\Entity\Device $device
     *
     * @return Transaction
     */
    public function setDevice(\PaddlereBundle\Entity\Device $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return \PaddlereBundle\Entity\Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set field
     *
     * @param \PaddlereBundle\Entity\Field $field
     *
     * @return Transaction
     */
    public function setField(\PaddlereBundle\Entity\Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \PaddlereBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set host
     *
     * @param \PaddlereBundle\Entity\Host $host
     *
     * @return Transaction
     */
    public function setHost(\PaddlereBundle\Entity\Host $host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return \PaddlereBundle\Entity\Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set guest
     *
     * @param \PaddlereBundle\Entity\Guest $guest
     *
     * @return Transaction
     */
    public function setGuest(\PaddlereBundle\Entity\Guest $guest = null)
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
     * Set event
     *
     * @param \PaddlereBundle\Entity\Event $event
     *
     * @return Transaction
     */
    public function setEvent(\PaddlereBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \PaddlereBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
