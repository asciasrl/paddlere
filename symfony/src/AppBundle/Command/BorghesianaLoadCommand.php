<?php

namespace AppBundle\Command;

use AppBundle\Entity\BorghesianaLog;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Finder\Finder;


class BorghesianaLoadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:borghesiana:load')
            ->setDescription('Loads Borghesiana logs')
            ->addArgument('dir',InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->in($input->getArgument('dir'))->name('*.csv')->sortByName();
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repoBorghesianaLog = $em->getRepository('AppBundle\Entity\BorghesianaLog');
        foreach ($finder as $file) {
            $output->write('Reading ' . $file->getRelativePathname() . ':');
            $contents = explode("\r\n",$file->getContents());
            $headers = array_map('trim',explode(",",array_shift($contents)));
            if (count($headers) != 3) {
                $output->writeln(' invalid header');
                continue;
            }
            //var_dump($headers);
            $totals=0;
            $dupes=0;
            $added=0;
            while ($r = array_shift($contents)) {
                //var_dump($r);
                $content = array_map('trim',explode(",",$r));
                if (empty($content)) {
                    continue;
                }
                $totals++;
                //var_dump($content);
                $row = array_combine($headers,$content);
                $dataora = \DateTime::createFromFormat('d/m/Y H:i:s',$row['Data'] . ' ' . $row['Ora']);
                if ($dataora === false) {
                    $this->getContainer()->get('logger')->warn(sprintf("In '%s' row %d invalid row: '%s'",$file->getRelativePathname(),$totals,$r));
                    continue;
                }
                $log = $repoBorghesianaLog->findOneBy(array('dataora' => $dataora));
                if ($log) {
                    //var_dump($log);
                    $dupes++;
                    continue;
                }
                $log = new BorghesianaLog();
                $log->setDataora($dataora);
                $log->setEvento($row['Evento']);
                $em->persist($log);
                $added++;
            }
            $em->flush();
            $output->writeln(sprintf(' loaded %d events, %d added, %d dupes.',$totals,$added,$dupes));
        }
    }
}