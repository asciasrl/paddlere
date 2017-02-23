<?php

namespace PaddlereBundle\Controller;

use PaddlereBundle\Entity\Device;
use PaddlereBundle\Entity\DeviceManager;
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
     * @Route(
     *     "slimline/{deviceId}/{operation}/{param}",
     *     name="slimline",
     *     defaults={"continuation": "0"}
     * )
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
        $log->info(sprintf("Ping device %s", $device));
        $deviceManager->ping($device);

        switch ($operation) {
            case 1: // no operation- ping only
                return new Response('',204);
                break;
            case 10: // Read a tag
                /** @var TagManager $tagmaneger */
                $tagmaneger = $this->get('paddlere.manager.tag');
                /** @var Tag $tag */
                $tag = $tagmaneger->findOneBy(array('serial' => $param, 'enabled' => true));
                if (empty($tag)) {
                    $msg = sprintf("Tag '%s' not found",$param);
                    $log->error($msg);
                    return new Response($msg,404);
                } else {
                    $tagmaneger->ping($tag);
                    $msg = sprintf("%s;%d;%d",$tag->getSerial(),$tag->getCredit(),$tag->getFun());  // ;%d;%s   ,$tag->getEnabled(),$tag->getName()
                    $log->info($msg);
                    return new Response($msg);
                }
                break;
            default:
                return new Response('',410);
        }

    }

}
