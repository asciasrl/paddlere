<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="facility")
 * @ORM\Entity(repositoryClass="PaddlereBundle\Repository\FacilityRepository")
 */
class Facility
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
        return $this->getName()?:'New';
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abuse_email", type="string", length=180, nullable=true)
     */
    protected $abuseEmail;

    /**
     * @var Field[]
     * @ORM\OneToMany(targetEntity="Field", mappedBy="facility"))
     */
    protected $fields;

    /**
     * @var Device[]
     * @ORM\OneToMany(targetEntity="Device", mappedBy="facility"))
     */
    protected $devices;

    /**
     * @var Tag[]
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="facility"))
     */
    protected $tags;

    /**
     * @var Guest[]
     * @ORM\OneToMany(targetEntity="Guest", mappedBy="facility"))
     */
    protected $guests;

    /**
     * @var Host[]
     * @ORM\OneToMany(targetEntity="Host", mappedBy="facility"))
     */
    protected $hosts;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Facility
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guests = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hosts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Facility
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
     * Add field
     *
     * @param \PaddlereBundle\Entity\Field $field
     *
     * @return Facility
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

    /**
     * Add device
     *
     * @param \PaddlereBundle\Entity\Device $device
     *
     * @return Facility
     */
    public function addDevice(\PaddlereBundle\Entity\Device $device)
    {
        $this->devices[] = $device;

        return $this;
    }

    /**
     * Remove device
     *
     * @param \PaddlereBundle\Entity\Device $device
     */
    public function removeDevice(\PaddlereBundle\Entity\Device $device)
    {
        $this->devices->removeElement($device);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Add tag
     *
     * @param \PaddlereBundle\Entity\Tag $tag
     *
     * @return Facility
     */
    public function addTag(\PaddlereBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \PaddlereBundle\Entity\Tag $tag
     */
    public function removeTag(\PaddlereBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add guest
     *
     * @param \PaddlereBundle\Entity\Guest $guest
     *
     * @return Facility
     */
    public function addGuest(\PaddlereBundle\Entity\Guest $guest)
    {
        $this->guests[] = $guest;

        return $this;
    }

    /**
     * Remove guest
     *
     * @param \PaddlereBundle\Entity\Guest $guest
     */
    public function removeGuest(\PaddlereBundle\Entity\Guest $guest)
    {
        $this->guests->removeElement($guest);
    }

    /**
     * Get guests
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Add host
     *
     * @param \PaddlereBundle\Entity\Host $host
     *
     * @return Facility
     */
    public function addHost(\PaddlereBundle\Entity\Host $host)
    {
        $this->hosts[] = $host;

        return $this;
    }

    /**
     * Remove host
     *
     * @param \PaddlereBundle\Entity\Host $host
     */
    public function removeHost(\PaddlereBundle\Entity\Host $host)
    {
        $this->hosts->removeElement($host);
    }

    /**
     * Get hosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Facility
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
     * Set abuseEmail
     *
     * @param string $abuseEmail
     *
     * @return Facility
     */
    public function setAbuseEmail($abuseEmail)
    {
        $this->abuseEmail = $abuseEmail;

        return $this;
    }

    /**
     * Get abuseEmail
     *
     * @return string
     */
    public function getAbuseEmail()
    {
        return $this->abuseEmail;
    }
}
