<?php

namespace PaddlereBundle\Controller;

use PaddlereBundle\Entity\Device;
use PaddlereBundle\Entity\DeviceManager;
use PaddlereBundle\Entity\Event;
use PaddlereBundle\Entity\EventManager;
use PaddlereBundle\Entity\Field;
use PaddlereBundle\Entity\FieldManager;
use PaddlereBundle\Entity\Guest;
use PaddlereBundle\Entity\GuestManager;
use PaddlereBundle\Entity\Host;
use PaddlereBundle\Entity\Tag;
use PaddlereBundle\Entity\TagManager;
use PaddlereBundle\Entity\Transaction;
use PaddlereBundle\Entity\TransactionManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('PaddlereBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     * @param string $deviceId
     * @param int $operation
     * @param mixed $param
     * @Route(
     *     "slimline/{deviceId}/{operation}/{param}",
     *     name="slimline",
     *     defaults={"param": null}
     * )
     * @return Response
     */
    public function slimlineAction(Request $request,$deviceId,$operation,$param)
    {
        /** @var LoggerInterface $log */
        $log = $this->get('logger');

        //$deviceId = $request->query->getInt('D');
        if (empty($deviceId)) {
            $msg = "DeviceID not given";
            $log->error($msg);
            return new Response($msg,403);
        }
        /** @var DeviceManager $deviceManager */
        $deviceManager = $this->get('paddlere.manager.device');
        /** @var Device $device */
        $device = $deviceManager->findOneBy(array('serial' => $deviceId));
        if (empty($device)) {
            $msg = sprintf("Device %d not found",$deviceId);
            $log->error($msg);
            return new Response($msg,404);
        }
        $log->info(sprintf("Ping device '%s'", $device));
        $deviceManager->ping($device);

        switch ($operation) {
            case 1: // no operation- ping only

                return new Response(sprintf('1:%s',$device->getLastseenAt()->format('r')));
                break;

            case 10: // Read a tag

                /** @var TagManager $tagManager */
                $tagManager = $this->get('paddlere.manager.tag');
                /** @var Tag $tag */
                $tag = $tagManager->findOneBy(array('serial' => $param));
                if (empty($tag)) {
                    $msg = sprintf("Tag '%s' not found",$param);
                    $log->error($msg);
                    return new Response($msg,404);
                } else {
                    $tagManager->ping($tag);
                    /** @var Guest $guest */
                    $guest = $tag->getGuest();
                    if (empty($guest)) {
                        $msg = sprintf("No guest associated to tag '%s'",$tag);
                        $log->error($msg);
                        return new Response($msg,404);
                    } else {
                        $msg = sprintf("%s;%s;%d;%d;%d;%s",$tag->getSerial(),$guest->getId(),$guest->getCredit(),$guest->getFun(),$guest->getEnabled(),$guest->getName());
                        $log->info("Read Guest: " . $msg);
                        return new Response($msg);
                    }
                }
                break;

            case 11: // TAG credit usage

                /** @var TagManager $tagManager */
                $tagManager = $this->get('paddlere.manager.tag');
                /** @var Tag $tag */
                $tag = $tagManager->findOneBy(array('serial' => $param));
                if (empty($tag)) {
                    $msg = sprintf("Tag '%s' not found",$param);
                    $log->error($msg);
                    return new Response($msg,404);
                } else {
                    $tagManager->ping($tag);

                    $oldCredit = $tag->getCredit();
                    $newCredit = $request->query->getInt('Credit',0);
                    $useCredit = $request->query->getInt('Use',0);

                    $msg = sprintf("Tag=%s Credit old=%d use=%d new=%d",$tag->getSerial(),$oldCredit,$useCredit,$newCredit);

                    if (($newCredit - $oldCredit == $useCredit) & ($useCredit != 0)) {
                        $log->info($msg);
                        $tag->setCredit($newCredit);
                        $tagManager->save($tag);
                        $msg = sprintf("%s;%s;%d;%d;%d;%s",$tag->getSerial(),$tag->getPassword(),$tag->getCredit(),$tag->getFun(),$tag->getEnabled(),$tag->getName());
                        $log->info("Read TAG: " . $msg);
                        return new Response($msg);
                    } else {
                        $log->error("Invalid credit usage: " . $msg);
                        return new Response("Invalid credit amounts",404);
                    }

                }
                break;

            case 20: // Field event

                /** @var FieldManager $fieldManager */
                $fieldManager = $this->get('paddlere.manager.field');
                /** @var Field $field */
                $field = $fieldManager->findOneByDeviceField($device,$param);
                if (empty($field)) {
                    $msg = sprintf("Field '%s' of device '%s' not found",$param,$device);
                    $log->error($msg);
                    return new Response($msg,404);
                }

                $eventId = $request->query->get('Eid','');

                $eventTypeNum = $request->query->getInt('Use',-1);
                switch ($eventTypeNum) {
                    case 0:
                        $eventType = 'Libero';
                        break;
                    case 1:
                        $eventType = 'Affitto';
                        break;
                    case 2:
                        $eventType = 'Lezione';
                        break;
                    case 3:
                        $eventType = 'Promo';
                        break;
                    default:
                        $msg = sprintf("Unknown event type num %d",$eventTypeNum);
                        $log->error($msg);
                        return new Response($msg,400);
                }

                $guestId = $request->query->get('Gid','');
                if (empty($guestId)) {
                    if (empty($eventId)) {
                        $msg = "Guest ID must be specified for new event";
                        $log->error($msg);
                        return new Response($msg, 400);
                    } else {
                        $guest = null;
                    }
                } else {
                    /** @var GuestManager $guestManager */
                    $guestManager = $this->get('paddlere.manager.guest');
                    /** @var Guest $guest */
                    $guest = $guestManager->find($guestId);
                    if (empty($guest)) {
                        $msg = sprintf("Guest id '%s' not found",$guestId);
                        $log->error($msg);
                        return new Response($msg,404);
                    }
                }

                $hostId = $request->query->get('Hid','');
                if (empty($hostId)) {
                    $log->debug("Host ID not specified");
                    $host = null;
                } else {
                    /** @var GuestManager $guestManager */
                    $hostManager = $this->get('paddlere.manager.host');
                    /** @var Host $host */
                    $host = $hostManager->find($hostId);
                    if (empty($host)) {
                        $msg = sprintf("Host id '%s' not found",$guestId);
                        $log->error($msg);
                        return new Response($msg,404);
                    }
                }

                $datetimeBegin = \DateTime::createFromFormat('U',$request->query->getInt('Beg',0));
                $datetimeEnd = \DateTime::createFromFormat('U',$request->query->getInt('End',0));

                // TODO check datetime

                /** @var EventManager $eventManager */
                $eventManager = $this->get('paddlere.manager.event');
                /** @var TransactionManager $transactionManager */
                $transactionManager = $this->get('paddlere.manager.transaction');

                /** @var Transaction $transaction */
                $transaction = $transactionManager->create();
                $transaction->setFacility($field->getFacility());
                $transaction->setDevice($device);
                $transaction->setField($field);
                $transaction->setGuest($guest);
                $transaction->setHost($host);
                $transaction->setCreatedAt(new \DateTime());

                if (empty($eventId)) {
                    /** @var Event $event */
                    $event = $eventManager->create();
                    $event->setField($field);
                    $event->setDevice($device);
                    $event->setGuest($guest);
                    $event->setHost($host);
                    $event->setDatetimeBegin($datetimeBegin);
                    $event->setDatetimeEnd($datetimeEnd);
                    $event->setEventType($eventType);

                    $transaction->setAmount($event->getDuration());
                    $transaction->setAccountedAt($datetimeBegin);
                    $transaction->setEvent($event);
                    $transactionManager->save($transaction);  // cascade save event

                    $log->info(sprintf("Created new event '%s'",$event->getId()));
                    $msg = sprintf("%d;%s",$param,$event->getId());
                    return new Response($msg);
                } else {
                    /** @var Event $event */
                    $event = $eventManager->find($eventId);
                    if ($event) {
                        // TODO check matching of others data
                        if ($event->getEventType() != $eventType) {
                            $log->info(sprintf("Change event '%s' from type '%s', to '%s'",$event,$event->getEventType(),$eventType));
                            $event->setEventType($eventType);
                            $eventManager->save($event);
                        }
                        if (!($event->getDatetimeEnd() == $datetimeEnd)) {
                            $log->info(sprintf("Change event '%s' ending from '%s', to '%s'",$event,$event->getDatetimeEnd()->format('c'),$datetimeEnd->format('c')));
                            $durationBefore=$event->getDuration();
                            $log->debug("before:" . $durationBefore);
                            $event->setDatetimeBegin($datetimeBegin);
                            $event->setDatetimeEnd($datetimeEnd);
                            $durationAfter=$event->getDuration();
                            $log->debug("after:" . $durationAfter);
                            $transaction->setAmount($durationAfter - $durationBefore);
                            $transaction->setAccountedAt(new \DateTime());
                            $transaction->setEvent($event);
                            $transactionManager->save($transaction);  // cascade save event
                        }
                        return new Response('',200);
                    } else {
                        $log->critical(sprintf("Event not found with id '%s'",$eventId));
                    }
                }
                break;

            case 30: // Field abuse

                /** @var FieldManager $fieldManager */
                $fieldManager = $this->get('paddlere.manager.field');
                /** @var Field $field */
                $field = $fieldManager->findOneByDeviceField($device,$param);
                if (empty($field)) {
                    $msg = sprintf("Field '%s' of device '%s' not found",$param,$device);
                    $log->error($msg);
                    return new Response($msg,404);
                }

                $datetime = \DateTime::createFromFormat('U',$request->query->getInt('T',0));
                $log->warning(sprintf("Abuse on field '%s' at '%s'",$field,$datetime->format('c')));

                /** @var EventManager $eventManager */
                $eventManager = $this->get('paddlere.manager.event');
                /** @var Event $event */
                $event = $eventManager->create();

                $event->setField($field);
                $event->setDevice($device);
                $event->setDatetimeBegin($datetime);
                $event->setEventType('Abuso');
                $eventManager->save($event);

                $log->info(sprintf("Created new event '%s'",$event->getId()));
                $msg = sprintf("%d;%s",$param,$event->getId());
                return new Response($msg);

                break;

            default:
                $log->alert(sprintf("Unknow operation '%d'",$operation));
                return new Response('',410);
        }

    }

}
