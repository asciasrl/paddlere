<?php


namespace PaddlereBundle\Service;


use PaddlereBundle\Entity\DeviceManager;
use PaddlereBundle\Entity\Event;
use PaddlereBundle\Entity\EventManager;
use PaddlereBundle\Entity\Field;
use PaddlereBundle\Entity\FieldManager;
use Psr\Log\LoggerInterface;

class EventService
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var DeviceManager
     */
    private $deviceManager;

    /**
     * @var FieldManager
     */
    private $fieldManager;

    public function __construct(LoggerInterface $logger, EventManager $eventManager, DeviceManager $deviceManager, FieldManager $fieldManager)
    {
        $this->logger = $logger;
        $this->eventManager= $eventManager;
        $this->deviceManager = $deviceManager;
        $this->fieldManager = $fieldManager;
    }

    /**
     * @param $deviceSerial string The serial of the device
     * @param $eventType string Type of event
     * @param $datetimeFrom \DateTime Starting time
     * @param $fieldName string Name of the field
     * @param $datetimeTo \DateTime Starting time
     */
    public function addEvent($deviceSerial,$eventType,$datetimeFrom=null,$fieldName='',$datetimeTo=null)
    {
        $device = $this->deviceManager->findOneBy(array('serial' => $deviceSerial));
        if (empty($device)) {
            $this->logger->critical(sprintf("Device with serial '%s' not found", $deviceSerial));
            return false;
        }

        /** @var Event $event */
        $event = $this->eventManager->create();
        $event->setDevice($device);

        $event->setEventType($eventType);

        if (empty($datetimeFrom)) {
            $event->setDatetimeFrom(new \DateTime());
        } else {
            $event->setDatetimeFrom($datetimeFrom);
        }
        if (empty($datetimeTo)) {
            $event->setDatetimeTo($event->getDatetimeFrom());
        } else {
            $event->setDatetimeTo($datetimeTo);
        }

        if (!empty($fieldName)) {
            /** @var Field $field */
            $field = $this->fieldManager->findOneBy(array('device' => $device, 'name' => $fieldName));
            if (empty($field)) {
                $this->logger->error(sprintf("Field with name '%s' not found for device '%s'", $fieldName,$device));
            } else {
                $event->setField($field);
            }
        }

        $this->eventManager->save($event);

        return $event;
    }

}