<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="guest",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="facility_name", columns={"name", "facility_id"})
 *     })
 * @ORM\Entity
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
    protected $tag;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    // l
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add tag
     *
     * @param \PaddlereBundle\Entity\Tag $tag
     *
     * @return Guest
     */
    public function addTag(\PaddlereBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \PaddlereBundle\Entity\Tag $tag
     */
    public function removeTag(\PaddlereBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTag()
    {
        return $this->tag;
    }
}
