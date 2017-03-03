<?php

namespace PaddlereBundle\Entity;

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
     * @var string Libero Affitto Lezione Promo Prenotazione Absuo
     * @ORM\Column(type="string", length=12)
     */
    protected $eventType;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $datetimeFrom;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $datetimeTo;

    /**
     * @var int Durata arrotondata in minuti
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $duration;

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
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function doSetDuration()
    {
        if (!is_null($this->getDatetimeTo())) {
            $interval =  $this->getDatetimeFrom()->diff($this->getDatetimeTo());
            $this->setDuration(round(($interval->h*3600+$interval->i*60+$interval->s)/60));
        }
    }

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Event


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
     * Set datetimeFrom
     *
     * @param \DateTime $datetimeFrom
     *
     * @return Event
     */
    public function setDatetimeFrom($datetimeFrom)
    {
        $this->datetimeFrom = $datetimeFrom;

        return $this;
    }

    /**
     * Get datetimeFrom
     *
     * @return \DateTime
     */
    public function getDatetimeFrom()
    {
        return $this->datetimeFrom;
    }

    /**
     * Set datetimeTo
     *
     * @param \DateTime $datetimeTo
     *
     * @return Event
     */
    public function setDatetimeTo($datetimeTo)
    {
        $this->datetimeTo = $datetimeTo;

        return $this;
    }

    /**
     * Get datetimeTo
     *
     * @return \DateTime
     */
    public function getDatetimeTo()
    {
        return $this->datetimeTo;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Event
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
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
}
