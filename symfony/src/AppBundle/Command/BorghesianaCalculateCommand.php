<?php

namespace AppBundle\Command;

use AppBundle\Entity\BorghesianaLog;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PaddlereBundle\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Finder\Finder;


class BorghesianaCalculateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:borghesiana:calculate')
            ->setDescription('Calculate use based on Borghesiana logs')
            ->addOption('all',null,InputOption::VALUE_NONE,"Recalculate all");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EventService $eventService */
        $eventService = $this->getContainer()->get('paddlere.service.event');

        $em = $this->getContainer()->get('doctrine')->getManager();
        // @var EntityRepository
        $repoBorghesianaLog = $em->getRepository('AppBundle\Entity\BorghesianaLog');
        $criteria = Criteria::create()
            ->orderBy(array("dataora" => Criteria::ASC));
        if (!$input->getOption('all')) {
            $criteria->where(Criteria::expr()->isNull('fine'));
        }
        $logs = $repoBorghesianaLog->matching($criteria);

        /**
         * @var BorghesianaLog[] $inizi
         */
        $inizi = array();
        /**
         * @var BorghesianaLog $log
         */
        foreach ($logs as $log) {
            $evento = $log->getEvento();
            list($tipo,$campo) = array_pad(explode(' ',$evento,2), 2 , null);
            if ($tipo == "Dummy") {
                $output->writeLn($log->getDataora()->format('c') . ' ' . $tipo);
                $log->setTipo($tipo);
                $log->setInizio($log->getDataora());
                $log->setFine($log->getDataora());
            }
            if ($tipo == "Inizio") {
                if (!array_key_exists($campo,$inizi) || $inizi[$campo]==null) {
                    $log->setTipo($tipo);
                    $log->setInizio($log->getDataora());
                    $output->writeLn($log->getDataora()->format('c') . ' Saving ' . $tipo . ' for ' . $campo);
                    $inizi[$campo]=$log;
                } else {
                    $interval =  $inizi[$campo]->getDataora()->diff($log->getDataora());
                    //var_dump($interval);
                    if ($interval->days > 1) {
                        $output->writeLn('Orpham ' . $inizi[$campo]);
                        $inizi[$campo]->setTipo($tipo . ' Orphan');
                        $inizi[$campo]->setFine($log->getDataora());
                        $output->writeLn($log->getDataora()->format('c') . ' Replacing ' . $tipo . ' for ' . $campo);
                        $inizi[$campo]=$log;
                    } else {
                        $output->writeLn($log->getDataora()->format('c') . ' Duplicated ' . $tipo . ' for ' . $campo);
                        $log->setInizio($inizi[$campo]->getDataora());
                        $log->setFine($log->getDataora());
                        $log->setTipo($tipo . ' Dup');
                    }
                }
            }
            if ($tipo == "Fine" && array_key_exists($campo,$inizi)) {
                $output->writeLn($log->getDataora()->format('c') . ' Calculating ' . $tipo . ' for ' . $campo);
                $log->setTipo('Utilizzo');
                $log->setCampo($campo);
                $inizio =  $inizi[$campo];
                $log->setInizio($inizio->getDataora());
                $fine = $log->getDataora();
                $inizio->setFine($fine);
                $log->setFine($fine);
                $interval =  $inizio->getDataora()->diff($fine);
                $durata = round(($interval->h*3600+$interval->i*60+$interval->s)/60);
                $log->setDurata($durata);
                $inizio->setDurata($durata);
                $inizi[$campo] = null;

                $eventService->addEvent('4981104', 'Utilizzo', $inizio->getDataora(), $campo, $log->getDataora());
            }
            if ($tipo == "Abuso") {
                $output->writeLn($log->getDataora()->format('c') . ' ' . $tipo . ' for ' . $campo);
                $log->setTipo('Abuso');
                $log->setCampo($campo);
                $log->setInizio($log->getDataora());
                $log->setFine($log->getDataora());
                $eventService->addEvent('4981104', $tipo, $log->getDataora(),$campo);
            }
            $em->flush();
        }
    }
}
