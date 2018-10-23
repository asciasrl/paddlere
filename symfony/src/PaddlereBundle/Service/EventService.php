<?php


namespace PaddlereBundle\Service;


use Application\Sonata\MediaBundle\Entity\Media;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use PaddlereBundle\Entity\DeviceManager;
use PaddlereBundle\Entity\Event;
use PaddlereBundle\Entity\EventManager;
use PaddlereBundle\Entity\Field;
use PaddlereBundle\Entity\FieldManager;
use Psr\Log\LoggerInterface;
use Sonata\MediaBundle\Extra\ApiMediaFile;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

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

    /**
     * @var MediaProviderInterface
     */
    private $imageProvider;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $templating;

    public function __construct(LoggerInterface $logger, EventManager $eventManager, DeviceManager $deviceManager, FieldManager $fieldManager, MediaProviderInterface $imageProvider, Client $httpClient, \Swift_Mailer $mailer, $templating)
    {
        $this->logger = $logger;
        $this->eventManager = $eventManager;
        $this->deviceManager = $deviceManager;
        $this->fieldManager = $fieldManager;
        $this->imageProvider = $imageProvider;
        $this->httpClient = $httpClient;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param $deviceSerial string The serial of the device
     * @param $eventType string Type of event
     * @param $datetimeBegin \DateTime Starting time
     * @param $fieldNum int Number of the field
     * @param $datetimeEnd \DateTime Starting time
     */
    public function addEvent($deviceSerial, $eventType, $datetimeBegin = null, $fieldNum = 0, $datetimeEnd = null)
    {
        $device = $this->deviceManager->findOneBy(array('serial' => $deviceSerial));
        if (empty($device)) {
            $this->logger->critical(sprintf("Device with serial '%s' not found", $deviceSerial));
            return false;
        }
        $this->deviceManager->ping($device, $datetimeBegin);
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
            $field = $this->fieldManager->findOneByDeviceField($device, $fieldNum);
            if (empty($field)) {
                $this->logger->error(sprintf("Field n.%d not found for device '%s'", $fieldNum, $device));
            } else {
                $event->setField($field);
            }
        }

        if (!empty($event->getField())) {
            if (empty($event->getField()->getSnapshotUri())) {
                $this->logger->warning(sprintf("Cannot take snapshot, add uri to field '%s'",$field));
            } else {
                $this->takeSnapshot($event,false);
            }
        }

        $this->eventManager->save($event);

        return $event;
    }

    public function takeSnapshotId($id)
    {
        return $this->takeSnapshot($this->eventManager->find($id));
    }
    /**
     * Take a snapshot for the event
     * @param Event $event
     * @param boolean $andSave Alsa call eventManager->save
     * @return Media
     */
    public function takeSnapshot(Event $event, $andSave = true)
    {
        if (! empty($event->getSnapshot())) {
            $this->logger->error(sprintf("Event '%s' already has snapshot",$event));
            return null;
        }
        $uri = $event->getField()->getSnapshotUri();
        if (empty($uri)) {
            $this->logger->error(sprintf("Field of event '%s' don't have a snapshot uri",$event));
            return null;
        }

        $this->logger->info(sprintf("Taking a snapshot for '%s' from '%s'",$event,$uri));

        $media = new Media();
        $media->setContext('snapshot');
        $media->setProviderName('sonata.media.provider.image');

        $handle = tmpfile();

        try {
            $response = $this->httpClient->get($uri);
        } catch (\RuntimeException $e) {
            $this->logger->emergency($e);
            return null;
        }
        fwrite($handle, $response->getBody());

        $file = new ApiMediaFile($handle);

        $mimeTypeGuesser = MimeTypeGuesser::getInstance();
        $file->setMimetype($mimeTypeGuesser->guess($file->getRealPath()));

        $media->setBinaryContent($file);

        $media->setName(sprintf("Snapshot at %s for %s",(new \DateTime())->format('c'), $event));
        $media->setEnabled(true);

        $this->imageProvider->updateMetadata($media);

        $event->setSnapshot($media);
        if ($andSave) {
            $this->eventManager->save($event);
        }

        return $event->getSnapshot();
    }

    /**
     * Notify the event by email using Field->abuseEmail
     * @param Event $event
     * @return int The number of successful recipients. Can be 0 which indicates failure
     */
    public function notify(Event $event)
    {
        if (empty($event->getDevice()->getFacility()->getAbuseEmail())) {
            $this->logger->warning(sprintf("Empty abuse email for facility '%s'",$event->getDevice()->getFacility()));
            return null;
        }
        /** @var \Swift_Message $message */
        $message = $this->mailer->createMessage();
        $message
            ->setFrom("paddlere@ascia.net")
            ->setTo($event->getDevice()->getFacility()->getAbuseEmail())
            ->setCc("paddlere@gmail.com")
            ->setBody( $this->templating->render('Emails/event_notify.txt.twig', ['event' => $event ]));
        $message->setSubject(sprintf('PaddleRE: %s %s %s', $event->getEventType(), $event->getDevice(), $event->getField()?$event->getField()->getName():""));
        $snapshot = $event->getSnapshot();
        if (!empty($snapshot)) {
            $file = $this->imageProvider->getReferenceFile($snapshot);
            $this->logger->info(sprintf("Attach snapshot '%s'",$file->getName()));
            $message->attach(\Swift_Attachment::newInstance($file->getContent(),$file->getName(),$snapshot->getContentType()));
        }
        $this->logger->debug("Sending evant notify: " . $message);
        return $this->mailer->send($message);
    }

}