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
}
