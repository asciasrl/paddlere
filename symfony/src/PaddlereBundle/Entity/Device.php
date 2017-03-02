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
        return ($this->getFacility()? $this->getFacility() . ' - ':'') . $this->getName();
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
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility", inversedBy="devices")
     */
    protected $facility;

    /**
     * @var Field[]
     * @ORM\OneToMany(targetEntity="Field", mappedBy="device")
     */
    protected $fields;

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Device
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add field
     *
     * @param \PaddlereBundle\Entity\Field $field
     *
     * @return Device
     */
    public function addField(\PaddlereBundle\Entity\Field $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Remove field
     *
     * @param \PaddlereBundle\Entity\Field $field
     */
    public function removeField(\PaddlereBundle\Entity\Field $field)
    {
        $this->fields->removeElement($field);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }
}
