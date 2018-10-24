<?php

namespace PaddlereBundle\Controller;

use PaddlereBundle\Entity\Device;
use PaddlereBundle\Entity\DeviceManager;
use PaddlereBundle\Entity\Event;
use PaddlereBundle\Entity\EventManager;
use PaddlereBundle\Entity\Facility;
use PaddlereBundle\Entity\Field;
use PaddlereBundle\Entity\FieldManager;
use PaddlereBundle\Entity\Guest;
use PaddlereBundle\Entity\GuestManager;
use PaddlereBundle\Entity\Host;
use PaddlereBundle\Entity\HostManager;
use PaddlereBundle\Entity\Tag;
use PaddlereBundle\Entity\TagManager;
use PaddlereBundle\Entity\Transaction;
use PaddlereBundle\Entity\TransactionManager;
use PaddlereBundle\Service\EventService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $lastseenAt = $device->getLastseenAt();
        $log->info(sprintf("Ping device '%s' from %s", $device,join('/',$request->getClientIps())));
        $device->setRemoteIP($request->getClientIp());
        $deviceManager->ping($device);

        switch ($operation) {
            case 1: // no operation- ping and time sync
                if ($param > 0) {
                    $log->debug(sprintf("Last Ping Sequence=%d Actual=%d",$device->getLastPing(),$param));
                    if ($param > 1 && ($device->getLastPing()+1) == $param) {
                        // all right
                        $log->debug("Ping sequence ok");
                    } elseif ($device->getLastPing() == 65535 && $param=1) {
                        // rollover
                        $log->info("Ping sequence rollover");
                    } elseif ($device->getLastPing() == 0) {
                        $log->info("First seen ping");
                    } else {
                        /** @var EventManager $eventManager */
                        $eventManager = $this->get('paddlere.manager.event');
                        /** @var Event $event */
                        $event = $eventManager->create();

                        $event->setDevice($device);
                        $event->setDatetimeBegin($lastseenAt);
                        $event->setDatetimeEnd(new \DateTime());
                        if ($param < $device->getLastPing()) {
                            $log->warning(sprintf("Device reboot, ping=%u",$param));
                            $event->setEventType("Reboot");
                        } elseif ($param > 1) {
                            $log->warning(sprintf("Communication down, ping=%u",$param));
                            $event->setEventType("Disconnect");
                        }
                        $eventManager->save($event);

                        /** @var EventService $eventService */
                        $eventService = $this->get('paddlere.service.event');
                        $eventService->notify($event);
                    }
                    $device->setLastPing($param);
                    $deviceManager->getEntityManager()->flush();
                }

                $timezoneOffset = $request->query->getInt('Tzo',0);
                $datetime = \DateTime::createFromFormat('U',$request->query->getInt('T',0)-$timezoneOffset)->setTimezone((new \DateTime())->getTimezone());
                $delta = (new \DateTime())->getTimestamp() - $datetime->getTimestamp();
                $log->info(sprintf("Device '%s' time delta: %d",$device,$delta));
                return new Response(sprintf('%d',(new \DateTime())->getTimestamp()+$timezoneOffset));
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
                        /** @var GuestManager $guestManager */
                        $guestManager = $this->get('paddlere.manager.guest');
                        $guestManager->ping($guest);
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

            case 12: // Find a Guest by its name (and facility of the device)

                /** @var GuestManager $guestManager */
                $guestManager = $this->get('paddlere.manager.guest');
                /** @var Guest $guest */
                $guest = $guestManager->findOneBy(array('facility' => $device->getFacility(), 'name' => $param));
                if (empty($guest)) {
                    $msg = sprintf("Guest with name '%s' not found",$param);
                    $log->error($msg);
                    return new Response($msg,404);
                } else {
                    $guestManager->ping($guest);
                    $tag = $guest->getTags()->first();
                    if (empty($tag)) {
                        $msg = sprintf("Guest '%s' has no tag",$guest);
                        $log->error($msg);
                        return new Response($msg,404);
                    }
                    $msg = sprintf("%s;%s;%d;%d;%d;%s",$tag->getSerial(),$guest->getId(),$guest->getCredit(),$guest->getFun(),$guest->getEnabled(),$guest->getName());
                    //$msg = sprintf("%s;%d;%d;%d;%s",$guest->getId(),$guest->getCredit(),$guest->getFun(),$guest->getEnabled(),$guest->getName());
                    $log->info("Read Guest: " . $msg);
                    return new Response($msg);
                }
                break;

            case 13: // Find a Host by its PIN (and facility of the device)

                /** @var HostManager $hostManager */
                $hostManager = $this->get('paddlere.manager.host');
                /** @var Host $host */
                $host = $hostManager->findOneBy(array('facility' => $device->getFacility(), 'password' => $param));
                if (empty($host)) {
                    $msg = sprintf("Host with PIN '%s' not found",$param);
                    $log->error($msg);
                    return new Response($msg,404);
                } else {
                    $hostManager->ping($host);
                    $msg = sprintf("%s;%s;%d;%s",$host->getId(),$host->getPassword(),$host->getEnabled(),$host->getName());
                    $log->info("Read Host: " . $msg);
                    return new Response($msg);
                }
                break;

            case 20: // Field usage
            case 21: // Field usage modification

                /** @var FieldManager $fieldManager */
                $fieldManager = $this->get('paddlere.manager.field');
                /** @var Field $field */
                $field = $fieldManager->findOneByDeviceField($device,$param);
                if (empty($field)) {
                    $msg = sprintf("Field '%s' of device '%s' not found",$param,$device);
                    $log->error($msg);
                    return new Response($msg,404);
                }

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

                $timezoneOffset = $request->query->getInt('Tzo',0);
                $datetime = \DateTime::createFromFormat('U',$request->query->getInt('T',(new \DateTime())->getTimestamp())-$timezoneOffset)->setTimezone((new \DateTime())->getTimezone());
                $datetimeBegin = \DateTime::createFromFormat('U',$request->query->getInt('Beg',0)-$timezoneOffset)->setTimezone((new \DateTime())->getTimezone());
                $datetimeEnd = \DateTime::createFromFormat('U',$request->query->getInt('End',0)-$timezoneOffset)->setTimezone((new \DateTime())->getTimezone());
                $log->info(sprintf("Field:%s Begin:%s End:%s Tz:%s",$field,$datetimeBegin->format('c'),$datetimeEnd->format('c'),$datetimeEnd->getTimezone()->getName()));

                /** @var EventManager $eventManager */
                $eventManager = $this->get('paddlere.manager.event');

                $eventId = $request->query->get('Eid','');

                if (!empty($eventId)) {
                    $event = $eventManager->find($eventId);
                    if (empty($event)) {
                        $msg = sprintf("Invalid event id '%s'",$eventId);
                        $log->alert($msg);
                        return new Response($msg,404);
                    }
                } else {
                    /** @var Event $event */
                    $event = $eventManager->findOneBy(array('field' => $field, 'datetimeBegin' => $datetimeBegin));
                }
                if (empty($event)) {
                    $eventId = null;
                    $event = $eventManager->create();
                } else {
                    $eventId = $event->getId();
                    $log->info(sprintf("Found event '%s'",$event));
                }

                $guestId = $request->query->get('Gid','');
                if (empty($guestId)) {
                    if (empty($eventId)) {
                        $msg = "Guest ID must be specified for new event";
                        $log->alert($msg);
                        return new Response($msg, 400);
                    } else {
                        $guest = $event->getGuest();
                    }
                } else {
                    /** @var GuestManager $guestManager */
                    $guestManager = $this->get('paddlere.manager.guest');
                    /** @var Guest $guest */
                    $guest = $guestManager->find($guestId);
                    if (empty($guest)) {
                        $msg = sprintf("Guest id '%s' not found",$guestId);
                        $log->critical($msg);
                        return new Response($msg,404);
                    } else {
                        $log->info(sprintf("Guest '%s'",$guest));
                    }
                }

                $tagSerial = $request->query->get('Tag','');
                if (empty($tagSerial)) {
                    $tag = null;
                } else {
                    /** @var TagManager $tagManager */
                    $tagManager = $this->get('paddlere.manager.tag');
                    /** @var Tag $tag */
                    $tag =  $tagManager->findOneBy(array('serial' => $tagSerial));

                    if (empty($tag)) {
                        $log->alert(sprintf("Tag '%s' not found",$tagSerial));
                    } else {
                        /** @var Facility $tagFacility */
                        $tagFacility = $tag->getFacility();
                        if (empty($tagFacility)) {
                            $log->alert(sprintf("Tag '%s' don't belongs to any facility",$tagSerial));
                        } elseif ($tagFacility->getId() != $device->getFacility()->getId() ) {
                            $log->alert(sprintf("Tag '%s' don't belongs to facility '%s' but to '%s'",$tagSerial,$device->getFacility(),$tagFacility));
                        }

                        /** @var Guest $tagGuest */
                        $tagGuest = $tag->getGuest();
                        if (empty($tagGuest)) {
                            $log->alert(sprintf("Tag '%s' don't belongs to any guest",$tagSerial));
                        } elseif ($tagGuest->getId() != $guest->getId() ) {
                            $log->alert(sprintf("Tag '%s' don't belongs to guest '%s' but to '%s'",$tagSerial,$guest,$tagGuest));
                        } else {
                            $log->info(sprintf("Tag '%s' of guest '%s'",$tagSerial,$tagGuest));
                        }
                    }
                }

                $hostId = $request->query->get('Hid','');
                if (empty($hostId)) {
                    $log->debug("Host ID not specified");
                    $host = $event->getHost();
                } else {
                    /** @var GuestManager $guestManager */
                    $hostManager = $this->get('paddlere.manager.host');
                    /** @var Host $host */
                    $host = $hostManager->find($hostId);
                    if (empty($host)) {
                        $msg = sprintf("Host id '%s' not found",$guestId);
                        $log->critical($msg);
                        return new Response($msg,404);
                    } else {
                        $log->info(sprintf("Host '%s'",$host));
                    }
                }

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
                    $event->setField($field);
                    $event->setDevice($device);
                    $event->setGuest($guest);
                    $event->setTag($tag);
                    $event->setHost($host);
                    $event->setDatetimeBegin($datetimeBegin);
                    $event->setDatetimeEnd($datetimeEnd);
                    $event->setEventType($eventType);

                    $transaction->setAmount($event->getDuration());
                    $transaction->setAccountedAt($datetime);
                    $transaction->setEvent($event);
                    $transactionManager->save($transaction);  // cascade save event

                    $log->info(sprintf("Created new event '%s' id:%s",$event,$event->getId()));

                    // TEST: save snapshot also of regular use
                    /** @var EventService $eventService */
                    /*
                    $eventService = $this->get('paddlere.service.event');
                    $eventService->takeSnapshot($event);
                    $eventService->notify($event);
                    */

                    $msg = sprintf("%d;%s",$param,$event->getId());
                    return new Response($msg);
                } else {
                    if ($event->getEventType() != $eventType) {
                        $log->info(sprintf("Change event '%s' type from '%s' to '%s'",$event,$event->getEventType(),$eventType));
                        $event->setEventType($eventType);
                        $eventManager->save($event);  // save only event
                    }
                    if (!($event->getDatetimeEnd() == $datetimeEnd)) {
                        $log->info(sprintf("Change event '%s' ending from '%s' to '%s'",$event,$event->getDatetimeEnd()->format('c'),$datetimeEnd->format('c')));
                        $durationBefore=$event->getDuration();
                        $log->debug("before:" . $durationBefore);
                        $event->setDatetimeBegin($datetimeBegin);
                        $event->setDatetimeEnd($datetimeEnd);
                        $durationAfter=$event->getDuration();
                        $log->debug("after:" . $durationAfter);
                        $transaction->setAmount($durationAfter - $durationBefore);
                        $transaction->setAccountedAt($datetime);
                        $transaction->setEvent($event);
                        $transactionManager->save($transaction);  // update credit and cascade save event if needed
                    }
                    $msg = sprintf("%d;%s",$param,$event->getId());
                    return new Response($msg);
                }
                break;

            case 30: // Field abuse
            case 31: // Sensor A inactivity
            case 32: // Sensor B inactivity
            case 33: // Sensor A+B inactivity

                /** @var FieldManager $fieldManager */
                $fieldManager = $this->get('paddlere.manager.field');
                /** @var Field $field */
                $field = $fieldManager->findOneByDeviceField($device,$param);
                if (empty($field)) {
                    $msg = sprintf("Field '%s' of device '%s' not found",$param,$device);
                    $log->error($msg);
                    return new Response($msg,404);
                }

                $timezoneOffset = $request->query->getInt('Tzo',0);
                $datetime = \DateTime::createFromFormat('U',$request->query->getInt('T',0)-$timezoneOffset)->setTimezone((new \DateTime())->getTimezone());

                /** @var EventManager $eventManager */
                $eventManager = $this->get('paddlere.manager.event');
                /** @var Event $event */
                $event = $eventManager->create();

                $event->setField($field);
                $event->setDevice($device);
                $event->setDatetimeBegin($datetime);
                switch ($operation) {
                    case 30:
                        $event->setEventType('Abuso');
                        break;
                    case 31:
                        $event->setEventType('Inattivo A');
                        break;
                    case 32:
                        $event->setEventType('Inattivo B');
                        break;
                    case 33:
                        $event->setEventType('Inattivi A+B');
                        break;
                }
                $eventManager->save($event);

                $log->info(sprintf("Created new event '%s'",$event->getId()));
                $log->warning(sprintf("%s on field '%s' at '%s'",$event->getEventType(),$field,$datetime->format('c')));

                /** @var EventService $eventService */
                $eventService = $this->get('paddlere.service.event');
                $eventService->takeSnapshot($event);
                $eventService->notify($event);

                $msg = sprintf("%d;%s",$param,$event->getId());
                return new Response($msg);

                break;

            default:
                $log->alert(sprintf("Unknow operation '%d'",$operation));
                return new Response('',410);
        }

    }

}
