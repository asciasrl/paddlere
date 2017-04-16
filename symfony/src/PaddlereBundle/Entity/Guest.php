<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Device
 *
 * @ORM\Table(name="guest",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="facility_name", columns={"name", "facility_id"})
 *     })
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"name", "facility"}
 *     )
 */
class Guest
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
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility", inversedBy="guests")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id", nullable=false)
     */
    protected $facility;

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
     * @var Tag
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="guest")
     */
    protected $tags;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    // custome OneToMeny getters/setters

    /**
     * Add tag
     *
     * @param \PaddlereBundle\Entity\Tag $tag
     *
     * @return Guest
     */
    public function addTag(\PaddlereBundle\Entity\Tag $tag)
    {
        $tag->setGuest($this);
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
        $tag->setGuest(null);
        $this->tags->removeElement($tag);
    }


    //  bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Guest

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @return Guest
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
     * @return Guest
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
     * @return Guest
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
     * @return Guest
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
     * @return Guest
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
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
