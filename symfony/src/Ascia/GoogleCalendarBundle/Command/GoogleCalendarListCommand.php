<?php

namespace Ascia\GoogleCalendarBundle\Command;

use Ascia\GoogleCalendarBundle\GoogleCalendarService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class GoogleCalendarListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('google:calendar:list')
            ->setDescription('Lists available Google Calendars');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var GoogleCalendarService $calendarService */
        $calendarService = $this->getContainer()->get('ascia_google_calendar');

        try {
            /** @var \Google_Service_Calendar_CalendarList $calendars */
            $calendars = $calendarService->listCalendars();
            if ($calendars->count() > 1) {
                $choices = array();
                $items = array();
                /** @var \Google_Service_Calendar_CalendarListEntry $calendar */
                foreach ($calendars->getItems() as $calendar) {
                    $choices[$calendar->id] = $calendar->summary;
                    $items[$calendar->id] = $calendar;
                }
                $question = new ChoiceQuestion(
                    'Please select the calendar to list',
                    $choices,
                    0
                );
                /** @var Helper $helper */
                $helper = $this->getHelper('question');
                $calendarId = $helper->ask($input, $output, $question);
                /** @var \Google_Service_Calendar_Event $event */
                $events = $calendarService->getEvents($calendarId,null);
                foreach ($events as $event) {
                    $output->writeln(sprintf("%s;%s;%s;%s",$event->id, $event->getStart()->getDateTime(),$event->getEnd()->getdateTime(),$event->summary));
                }
                $output->writeln(sprintf("Next Sync Token: %s",$events->getNextSyncToken()));
            }
            //var_dump($calendars->getItems());
        } catch (\Google_Service_Exception $exception) {
            if ($exception->getCode()==401) {
                throw new AuthenticationException("Please use google:calendar:auth to obtain authorization");
            }
        }
   }
}