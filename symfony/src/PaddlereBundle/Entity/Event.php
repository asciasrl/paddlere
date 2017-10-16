<?php

namespace PaddlereBundle\Entity;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", options={"default": 0})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", options={"default": 0})
     */
    protected $updatedAt;

    /**
     * @var string Libero Affitto Lezione Promo Prenotazione Absuo
     * @ORM\Column(type="string", length=12)
     */
    protected $eventType;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $datetimeBegin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datetimeEnd;

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
     * @var Field
     * @ORM\ManyToOne(targetEntity="Tag")
     */
    protected $tag;

    /**
     * @var Guest
     * @ORM\ManyToOne(targetEntity="Guest")
     */
    protected $guest;

    /**
     * @var Host
     * @ORM\ManyToOne(targetEntity="Host")
     */
    protected $host;

    /**
     * @var Media
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade="persist")
     */
    protected $snapshot;

    /**
     * @var Transaction[]
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="event")
     */
    protected $transactions;

    public function getDuration()
    {
        if (!is_null($this->getDatetimeEnd())) {
            $interval =  $this->getDatetimeBegin()->diff($this->getDatetimeEnd());
            return round(($interval->h*3600+$interval->i*60+$interval->s)/60);
        }
    }

    public function __toString()
    {
        return $this->getDatetimeBegin()->format('c') . ' | ' . $this->getField();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Event

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Event
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Event
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set eventType
     *
     * @param string $eventType
     *
     * @return Event
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set datetimeBegin
     *
     * @param \DateTime $datetimeBegin
     *
     * @return Event
     */
    public function setDatetimeBegin($datetimeBegin)
    {
        $this->datetimeBegin = $datetimeBegin;

        return $this;
    }

    /**
     * Get datetimeBegin
     *
     * @return \DateTime
     */
    public function getDatetimeBegin()
    {
        return $this->datetimeBegin;
    }

    /**
     * Set datetimeEnd
     *
     * @param \DateTime $datetimeEnd
     *
     * @return Event
     */
    public function setDatetimeEnd($datetimeEnd)
    {
        $this->datetimeEnd = $datetimeEnd;

        return $this;
    }

    /**
     * Get datetimeEnd
     *
     * @return \DateTime
     */
    public function getDatetimeEnd()
    {
        return $this->datetimeEnd;
    }

    /**
     * Set device
     *
     * @param \PaddlereBundle\Entity\Device $device
     *
     * @return Event
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
     * @return Event
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
     * Set tag
     *
     * @param \PaddlereBundle\Entity\Tag $tag
     *
     * @return Event
     */
    public function setTag(\PaddlereBundle\Entity\Tag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \PaddlereBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set guest
     *
     * @param \PaddlereBundle\Entity\Guest $guest
     *
     * @return Event
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
     * Set host
     *
     * @param \PaddlereBundle\Entity\Host $host
     *
     * @return Event
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
     * Set snapshot
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $snapshot
     *
     * @return Event
     */
    public function setSnapshot(\Application\Sonata\MediaBundle\Entity\Media $snapshot = null)
    {
        $this->snapshot = $snapshot;

        return $this;
    }

    /**
     * Get snapshot
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }

    /**
     * Add transaction
     *
     * @param \PaddlereBundle\Entity\Transaction $transaction
     *
     * @return Event
     */
    public function addTransaction(\PaddlereBundle\Entity\Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param \PaddlereBundle\Entity\Transaction $transaction
     */
    public function removeTransaction(\PaddlereBundle\Entity\Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }
}
