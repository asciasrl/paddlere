<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="host",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="facility_name", columns={"name", "facility_id"})
 *     })
 * @ORM\Entity
 */
class Host
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
        return ($this->getFacility()? $this->getFacility() . ' | ':'') . $this->getName();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=8, nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=16)
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility", inversedBy="hosts")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id", nullable=false)
     */
    protected $facility;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastseen_at", type="datetime", nullable=true)
     */
    protected $lastseenAt;

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Host


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
     * Set password
     *
     * @param string $password
     *
     * @return Host
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
     * @return Host
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Host
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
     * Set facility
     *
     * @param \PaddlereBundle\Entity\Facility $facility
     *
     * @return Host
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
     * Set lastseenAt
     *
     * @param \DateTime $lastseenAt
     *
     * @return Host
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
}
