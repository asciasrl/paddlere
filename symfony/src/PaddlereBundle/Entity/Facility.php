<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="facility")
 * @ORM\Entity
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
        return $this->getName();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

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

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Facility
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
}
