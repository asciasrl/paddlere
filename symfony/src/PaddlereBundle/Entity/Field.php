<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="field")
 * @ORM\Entity
 */
class Field
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
        return ($this->getDevice()? $this->getDevice() . ' - ':'') . $this->getName();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var Device
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="fields"))
     */
    protected $device;

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/Field

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
     * @return Field
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
     * Set device
     *
     * @param \PaddlereBundle\Entity\Device $device
     *
     * @return Field
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
}
