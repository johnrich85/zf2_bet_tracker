<?php namespace Scraper\Casters;

use Matches\Service\Event;
use Matches\Service\MatchesService;
use Matches\Service\Sport;
use Matches\Service\Team;
use Scraper\Casters\Contract\Caster;

class GosuLoLCaster implements Caster
{
    /**
     * @var MatchesService
     */
    protected $matchesService;

    /**
     * @var Team
     */
    protected $teamService;

    /**
     * @var Sport
     */
    protected $sportService;

    /**
     * @var Event
     */
    protected $eventService;

    /**
     * @var array
     */
    protected $entities;

    /**
     * @var \DateTime
     */
    protected $startTime;

    /**
     * @param MatchesService $matchesService
     * @param Team $teamService
     * @param Sport $sportService
     * @param Event $eventService
     */
    public function __construct(
        MatchesService $matchesService,
        Team $teamService,
        Sport $sportService,
        Event $eventService
    ) {
        $this->matchesService = $matchesService;
        $this->teamService = $teamService;
        $this->sportService = $sportService;
        $this->eventService = $eventService;

        $this->startTime = $now = new \DateTime();

        $this->matchesService->clearEm();
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param array $data
     * @return null
     * @todo notify/log if fail
     */
    public function cast(array $data)
    {
        $sport = $this->sportService
            ->find(1);

        $data['sport'] = $sport;
        $data['event'] = $this->getEvent($data['event']);
        $data['first_team'] = $this->getTeam($data['first_team']);
        $data['second_team'] = $this->getTeam($data['second_team']);
        $data['date'] = $this->timeRemainingToDate($data['date']);
        $data['created_at'] = $this->startTime;
        $data['updated_at'] = $this->startTime;
        $data['winner'] = 0;

        $match = $this->matchesService
            ->newInstance($data);

        if ($match) {
            $this->entities[] = $match;
        } else {
            //todo notify/log
        }

    }

    /**
     * @param $date
     * @return \DateTime|null
     */
    protected function timeRemainingToDate($date)
    {
        if ($date == false) {
            return;
        }

        $startTime = $this->startTime->format("Y-m-d H:i:s");

        $dateTime = new \DateTime($startTime);

        $countDownParts = explode(' ', $date);

        if (count($countDownParts) == 0) {
            return;
        }

        foreach ($countDownParts as $interval) {
            $interval = $this->formatInterval($interval);

            if (!$interval) {
                break;
            }

            $dateTime->modify('+' . $interval);
        }

        return $dateTime;
    }

    /**
     * Converts shorthand time intervals to
     * DateTime supported values (e.g 'd' to 'days')
     *
     * @param $interval
     * @return string|void
     */
    protected function formatInterval($interval)
    {
        if (strpos($interval, 'd')) {
            $period = ' days';
        } elseif (strpos($interval, 'h')) {
            $period = ' hours';
        } elseif (strpos($interval, 'm')) {
            $period = ' minutes';
        } elseif (strpos($interval, 's')) {
            $period = ' seconds';
        } else {
            return;
        }

        return intval($interval) . $period;
    }

    /**
     * @param $source
     * @return mixed
     */
    protected function getEvent($source)
    {
        $event = $this->eventService
            ->findEventBySource($source);

        if (!$event) {
            $event = $this->eventService
                ->findDefault();
        }

        return $event;
    }

    /**
     * @param $name
     * @return Team|null|object
     */
    protected function getTeam($name)
    {
        $team = $this->teamService
            ->findOneBy(array('name' => $name));

        if ($team == null) {
            $data = [
                'name' => $name,
                'sport' => 1
            ];

            $team = $this->teamService
                ->create($data);
        }

        return $team;
    }
}