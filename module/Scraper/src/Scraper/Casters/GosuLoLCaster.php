<?php namespace Scraper\Casters;

use \Doctrine\ORM\EntityRepository;
use Matches\Entity\Match;
use Matches\Entity\Team;
use Scraper\Casters\Contract\Caster;

class GosuLoLCaster implements Caster
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var
     */
    protected $matchesService;

    /**
     * @var
     */
    protected $teamRepo;

    /**
     * @var array
     */
    protected $entities;

    /**
     * @var \DateTime
     */
    protected $startTime;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param $matchesService
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, $matchesService) {
        $this->em = $em;

        $this->matchesService = $matchesService;
        $this->teamRepo = $this->em->getRepository('Matches\Entity\Team');

        $this->startTime = $now = new \DateTime();

        $this->em->clear();
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
        $sport = $this->em
            ->getRepository('Matches\Entity\Sport')
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

        if($match) {
            $this->entities[] = $match;
        }
        else {
            //todo notify/log
        }

    }

    /**
     * @param $date
     * @return \DateTime|null
     */
    protected function timeRemainingToDate($date) {
        if($date == false) {
            return;
        }

        $startTime = $this->startTime->format("Y-m-d H:i:s");

        $dateTime = new \DateTime($startTime);

        $countDownParts = explode(' ', $date);

        if(count($countDownParts) == 0) {
            return;
        }

        foreach($countDownParts as $interval) {
            $interval = $this->formatInterval($interval);

            if(!$interval)
                break;

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
    protected function formatInterval($interval) {
        if(strpos($interval, 'd')) {
            $period = ' days';
        }
        elseif(strpos($interval, 'h')) {
            $period = ' hours';
        }
        elseif(strpos($interval, 'm')) {
            $period = ' minutes';
        }
        elseif(strpos($interval, 's')) {
            $period = ' seconds';
        }
        else {
            return;
        }

        return intval($interval) . $period;
    }

    /**
     * @param $source
     * @return mixed
     */
    protected function getEvent($source) {
        $repo = $this->em->getRepository('Matches\Entity\Event');

        $event = $repo->findEventBySource($source);

        if(!$event) {
            $event = $repo->findDefault();
        }

        return $event;
    }

    /**
     * @param $name
     * @return Team|null|object
     */
    protected function getTeam($name) {
        $team = $this->teamRepo
            ->findOneBy(array('name'=>$name));

        if($team == null) {
            $team = new Team();
            $team->setName($name);
            $team->setSport(1);

            $this->em->persist($team);
        }

        return $team;
    }
}