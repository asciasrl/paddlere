<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag
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
        return $this->getSerial()?:'New';
    }

    /**
     * @var string
     *
     * @ORM\Column(name="serial", type="string", length=12, unique=true)
     */
    protected $serial;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var Guest
     * @ORM\ManyToOne(targetEntity="Guest", inversedBy="tags")
     */
    protected $guest;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastseen_at", type="datetime", nullable=true)
     */
    protected $lastseenAt;

    /**
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility", inversedBy="tags")
     */
    protected $facility;

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Tag


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
     * @param string $serial
     *
     * @return Tag
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial
     *
     * @return string
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Tag
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set lastseenAt
     *
     * @param \DateTime $lastseenAt
     *
     * @return Tag
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
     * Set guest
     *
     * @param \PaddlereBundle\Entity\Guest $guest
     *
     * @return Tag
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
     * Set facility
     *
     * @param \PaddlereBundle\Entity\Facility $facility
     *
     * @return Tag
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
     * Set description
     *
     * @param string $description
     *
     * @return Tag
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
