<?php

namespace PaddlereBundle\Command;

use PaddlereBundle\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TakeEventSnapshotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('paddlere:event:takesnapshot')
            ->setDescription('Take a snapshot')
            ->addArgument('id',InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EventService $eventService */
        $eventService = $this->getContainer()->get('paddlere.service.event');

        $eventService->takeSnapshotId($input->getArgument('id'));
    }
}