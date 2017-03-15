<?php
/**
 * User: sergio
 * Date: 14/03/17
 */

namespace PaddlereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceField
 *
 * @ORM\Table(name="device_field")
 * @ORM\Entity
 */
class DeviceField
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var Device
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="deviceFields")
     */
    protected $device;

    /**
     * @var Field
     * @ORM\OneToOne(targetEntity="Field", inversedBy="deviceField")
     */
    protected $field;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $num;

    public function __toString()
    {
        return sprintf("%d: %s",$this->getNum(),$this->getField()->getName());
    }

    // bin/console doctrine:generate:entities --no-backup PaddlereBundle/Entity/DeviceField


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
     * Set num
     *
     * @param integer $num
     *
     * @return DeviceField
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set device
     *
     * @param \PaddlereBundle\Entity\Device $device
     *
     * @return DeviceField
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

    /**
     * Set field
     *
     * @param \PaddlereBundle\Entity\Field $field
     *
     * @return DeviceField
     */
    public function setField(\PaddlereBundle\Entity\Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \PaddlereBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }
}
