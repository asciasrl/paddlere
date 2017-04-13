<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="tag",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="facility_name", columns={"name", "facility_id"})
 *     })
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
        return $this->getSerial();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="serial", type="string", length=12, unique=true)
     */
    protected $serial;

    /**
     * @var Guest
     * @ORM\ManyToOne(targetEntity="Guest", inversedBy="Tag")
     */
    protected $guest;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=8)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=16)
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="credit", type="integer")
     */
    protected $credit;

    /**
     * @var bool
     *
     * @ORM\Column(name="fun", type="boolean")
     */
    protected $fun;

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
     * Set password
     *
     * @param string $password
     *
     * @return Tag
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
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
     * Set credit
     *
     * @param integer $credit
     *
     * @return Tag
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set fun
     *
     * @param boolean $fun
     *
     * @return Tag
     */
    public function setFun($fun)
    {
        $this->fun = $fun;

        return $this;
    }

    /**
     * Get fun
     *
     * @return boolean
     */
    public function getFun()
    {
        return $this->fun;
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
}
