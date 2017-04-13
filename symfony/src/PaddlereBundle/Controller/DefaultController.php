<?php

namespace PaddlereBundle\Controller;

use PaddlereBundle\Entity\Device;
use PaddlereBundle\Entity\DeviceManager;
use PaddlereBundle\Entity\Event;
use PaddlereBundle\Entity\EventManager;
use PaddlereBundle\Entity\Field;
use PaddlereBundle\Entity\FieldManager;
use PaddlereBundle\Entity\Tag;
use PaddlereBundle\Entity\TagManager;
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

                return new Response(sprintf('1:%s'),$device->getLastseenAt());
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
                    $msg = sprintf("10=%s;%s;%d;%d;%d;%s",$tag->getSerial(),$tag->getPassword(),$tag->getCredit(),$tag->getFun(),$tag->getEnabled(),$tag->getName());
                    $log->info("Read TAG: " . $msg);
                    return new Response($msg);
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

                $tagSerial = $request->query->getAlnum('Tag','');
                if (empty($tagSerial)) {
                    $msg = "Tag not specified in query";
                    $log->error($msg);
                    return new Response($msg, 400);
                }
                /** @var TagManager $tagManager */
                $tagManager = $this->get('paddlere.manager.tag');
                /** @var Tag $tag */
                $tag = $tagManager->findOneBy(array('serial' => $tagSerial));
                if (empty($tag)) {
                    $msg = sprintf("Tag '%s' not found",$tagSerial);
                    $log->error($msg);
                    return new Response($msg,404);
                } else {
                    $tagManager->ping($tag);
                }

                $datetimeBegin = \DateTime::createFromFormat('U',$request->query->getInt('Beg',0));
                $datetimeEnd = \DateTime::createFromFormat('U',$request->query->getInt('End',0));

                // TODO check datetime

                /** @var EventManager $eventManager */
                $eventManager = $this->get('paddlere.manager.event');
                $eventId = $request->query->get('Eid','');
                if (empty($eventId)) {
                    /** @var Event $event */
                    $event = $eventManager->create();
                    $event->setField($field);
                    $event->setDevice($device);
                    $event->setTag($tag);
                    $event->setDatetimeBegin($datetimeBegin);
                    $event->setDatetimeEnd($datetimeEnd);
                    $event->setEventType($eventType);
                    $eventManager->save($event);
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
                        }
                        if (!($event->getDatetimeEnd() == $datetimeEnd)) {
                            $log->info(sprintf("Change event '%s' ending from '%s', to '%s'",$event,$event->getDatetimeEnd()->format('c'),$datetimeEnd->format('c')));
                            $event->setDatetimeEnd($datetimeEnd);
                        }
                        $eventManager->save($event);
                        return new Response('',200);
                    } else {
                        $log->error(sprintf("Event not found with id '%s'",$eventId));
                    }
                }
                break;

            default:
                $log->warning(sprintf("Unknow operation '%d'",$operation));
                return new Response('',410);
        }

    }

}
