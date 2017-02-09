<?php

namespace AppBundle\Command;

use AppBundle\Entity\BorghesianaLog;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
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
        $em = $this->getContainer()->get('doctrine')->getManager();
        // @var EntityRepository
        $repoBorghesianaLog = $em->getRepository('AppBundle\Entity\BorghesianaLog');
        $criteria = Criteria::create()
            ->orderBy(array("dataora" => Criteria::ASC));
        if (!$input->getOption('all')) {
            $criteria->where(Criteria::expr()->isNull('tipo'));
        }
        $logs = $repoBorghesianaLog->matching($criteria);

        $inizi = array();
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
                $log->setTipo($tipo);
                if (!array_key_exists($campo,$inizi) || $inizi[$campo]==null) {
                    $output->writeLn($log->getDataora()->format('c') . ' Saving ' . $tipo . ' for ' . $campo);
                    $inizi[$campo]=$log->getDataora();
                } else {
                    $output->writeLn($log->getDataora()->format('c') . ' Duplicated ' . $tipo . ' for ' . $campo);
                }
            }
            if ($tipo == "Fine" && array_key_exists($campo,$inizi)) {
                $output->writeLn($log->getDataora()->format('c') . ' Calculating ' . $tipo . ' for ' . $campo);
                $log->setTipo('Utilizzo');
                $log->setCampo($campo);
                $inizio =  $inizi[$campo];
                $log->setInizio($inizio);
                $fine = $log->getDataora();
                $log->setFine($fine);
                $interval =  $inizio->diff($fine);
                $durata = round(($interval->h*3600+$interval->i*60+$interval->s)/60);
                $log->setDurata($durata);
                $inizi[$campo] = null;
            }
            if ($tipo == "Abuso") {
                $output->writeLn($log->getDataora()->format('c') . ' ' . $tipo . ' for ' . $campo);
                $log->setTipo('Abuso');
                $log->setCampo($campo);
                $log->setInizio($log->getDataora());
                $log->setFine($log->getDataora());
                $log->setDurata(0);
            }
            $em->flush();
        }
    }
}
