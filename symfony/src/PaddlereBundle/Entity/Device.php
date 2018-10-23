<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity
 */
class Device
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    public function __toString()
    {
        return ($this->getFacility() ? $this->getFacility() . ' | ':'') . $this->getName();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="serial", type="integer", unique=true)
     */
    protected $serial;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="lastseen_at", type="datetime", nullable=true)
     */
    protected $lastseenAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastseen_ping", type="integer")
     */
    protected $lastPing;

    /**
     * @var \String
     *
     * @ORM\Column(name="remote_ip", type="string", length=45, nullable=true)
     */
    protected $remoteIP;

    /**
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility", inversedBy="devices")
     */
    protected $facility;

    /**
     * @var DeviceField[]
     * @ORM\OneToMany(targetEntity="DeviceField", mappedBy="device", cascade={"all"})
     */
    protected $deviceFields;

    /**
     * Add deviceField
     *
     * @param \PaddlereBundle\Entity\DeviceField $deviceField
     *
     * @return Device
     */
    public function addDeviceField(\PaddlereBundle\Entity\DeviceField $deviceField)
    {
        $deviceField->setDevice($this);

        $this->deviceFields[] = $deviceField;

        return $this;
    }

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Device
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deviceFields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set serial
     *
     * @param integer $serial
     *
     * @return Device
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial
     *
     * @return integer
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Device
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastseenAt
     *
     * @param \DateTime $lastseenAt
     *
     * @return Device
     */
    public function setLastseenAt($lastseenAt)
    {
        $this->lastseenAt = $lastseenAt;

        return $this;
    }

    /**
     * Get lastseenAt
     *
     * @return \DateTime
     */
    public function getLastseenAt()
    {
        return $this->lastseenAt;
    }

    /**
     * Set facility
     *
     * @param \PaddlereBundle\Entity\Facility $facility
     *
     * @return Device
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
     * Remove deviceField
     *
     * @param \PaddlereBundle\Entity\DeviceField $deviceField
     */
    public function removeDeviceField(\PaddlereBundle\Entity\DeviceField $deviceField)
    {
        $this->deviceFields->removeElement($deviceField);
    }

    /**
     * Get deviceFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeviceFields()
    {
        return $this->deviceFields;
    }

    /**
     * Set remoteIP
     *
     * @param string $remoteIP
     *
     * @return Device
     */
    public function setRemoteIP($remoteIP)
    {
        $this->remoteIP = $remoteIP;

        return $this;
    }

    /**
     * Get remoteIP
     *
     * @return string
     */
    public function getRemoteIP()
    {
        return $this->remoteIP;
    }

    /**
     * Set lastPing
     *
     * @param integer $lastPing
     *
     * @return Device
     */
    public function setLastPing($lastPing)
    {
        $this->lastPing = $lastPing;

        return $this;
    }

    /**
     * Get lastPing
     *
     * @return integer
     */
    public function getLastPing()
    {
        return $this->lastPing;
    }
}
