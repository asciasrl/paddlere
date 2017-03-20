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
     * @param $datetimeBegin \DateTime Starting time
     * @param $fieldNum int Number of the field
     * @param $datetimeEnd \DateTime Starting time
     */
    public function addEvent($deviceSerial, $eventType, $datetimeBegin=null, $fieldNum=0, $datetimeEnd=null)
    {
        $device = $this->deviceManager->findOneBy(array('serial' => $deviceSerial));
        if (empty($device)) {
            $this->logger->critical(sprintf("Device with serial '%s' not found", $deviceSerial));
            return false;
        }
        $this->deviceManager->ping($device,$datetimeBegin);
        if ($eventType == 'Dummy') {
            return; // ping only
        }

        /** @var Event $event */
        $event = $this->eventManager->create();
        $event->setDevice($device);

        $event->setEventType($eventType);

        if (empty($datetimeBegin)) {
            $event->setDatetimeBegin(new \DateTime());
        } else {
            $event->setDatetimeBegin($datetimeBegin);
        }
        if (!empty($datetimeEnd)) {
            $event->setDatetimeEnd($datetimeEnd);
        }

        if ($fieldNum > 0) {
            /** @var Field $field */
            $field = $this->fieldManager->findOneByDeviceField($device,$fieldNum);
            if (empty($field)) {
                $this->logger->error(sprintf("Field n.%d not found for device '%s'", $fieldNum,$device));
            } else {
                $event->setField($field);
            }
        }

        $this->eventManager->save($event);

        return $event;
    }

}