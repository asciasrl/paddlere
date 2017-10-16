<?php

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="field",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="name_facility_idx", columns={"facility_id","name"})}
 *     )
 * @ORM\Entity
 */
class Field
{
    /**
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var Facility
     * @ORM\ManyToOne(targetEntity="Facility", inversedBy="fields"))
     */
    protected $facility;

    /**
     * @var DeviceField
     * @ORM\OneToOne(targetEntity="DeviceField", mappedBy="field"))
     */
    protected $deviceField;

    /**
     * @var string
     * @ORM\Column(name="snapshotUri", type="string", length=255, nullable=true)
     */
    protected $snapshotUri;

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
     * Set facility
     *
     * @param \PaddlereBundle\Entity\Facility $facility
     *
     * @return Field
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
     * Set deviceField
     *
     * @param \PaddlereBundle\Entity\DeviceField $deviceField
     *
     * @return Field
     */
    public function setDeviceField(\PaddlereBundle\Entity\DeviceField $deviceField = null)
    {
        $this->deviceField = $deviceField;

        return $this;
    }

    /**
     * Get deviceField
     *
     * @return \PaddlereBundle\Entity\DeviceField
     */
    public function getDeviceField()
    {
        return $this->deviceField;
    }

    /**
     * Set snapshotUri
     *
     * @param string $snapshotUri
     *
     * @return Field
     */
    public function setSnapshotUri($snapshotUri)
    {
        $this->snapshotUri = $snapshotUri;

        return $this;
    }

    /**
     * Get snapshotUri
     *
     * @return string
     */
    public function getSnapshotUri()
    {
        return $this->snapshotUri;
    }
}
