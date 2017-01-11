<?php

namespace Ascia\GoogleCalendarBundle\Command;

use Ascia\GoogleCalendarBundle\GoogleCalendarService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GoogleCalendarAuthCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('google:calendar:auth')
            ->setDescription('Authorize this client to access Google Calendars');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var GoogleCalendarService $calendar */
        $calendar = $this->getContainer()->get('ascia_google_calendar');

        $output->writeln(sprintf("Open the following link in your browser:\n%s\n", $calendar->getAuthUrl()));

        $question = new Question('Enter verification code: ');

        $helper = $this->getHelper('question');
        $code = $helper->ask($input, $output, $question);

        $calendar->accessWithAuthCode($code);

   }
}