<?php namespace Scraper\Casters;

use \Doctrine\ORM\EntityRepository;
use Matches\Entity\Event;
use Matches\Entity\Match;
use Matches\Entity\Sport;
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
    protected $matchRepo;

    /**
     * @var
     */
    protected $teamRepo;

    /**
     * @var array
     */
    protected $entities;

    /**
     * GosuLoLCaster constructor.
     * @param EntityRepository $matchRepo
     * @param EntityRepository $teamRepo
     * @todo - fix static event/sport.
     */
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;

        $this->matchRepo = $this->em->getRepository('Matches\Entity\Match');
        $this->teamRepo = $this->em->getRepository('Matches\Entity\Team');
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
     */
    public function cast(array $data)
    {
        $this->em->clear();

        $sport = $this->em->getRepository('Matches\Entity\Sport')
            ->find( 1);

        $event = $this->em->getRepository('Matches\Entity\Event')
            ->find( 1);

        $now = new \DateTime();

        foreach($data as $key=>$matchData) {

            $first_team = $this->getTeam($matchData['opponent1']);
            $second_team = $this->getTeam($matchData['opponent2']);

            $match = new Match();
            $match->setFirstTeam($first_team);
            $match->setSecondTeam($second_team);
            $match->setSport($sport);
            $match->setCreatedAt($now);
            $match->setUpdatedAt($now);
            $match->setWinner(0);

            //todo - fix date and event
            //todo - check if match already existst, hash objecT?
            //todo - properly handle persitence and validation.
            $match->setEvent($event);
            $match->setDate($now);

            $this->entities[] = $match;

            $this->create($match);
        }

        $this->em->flush();
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

            $this->create($team);
        }

        return $team;
    }

    /**
     * @param $entity
     * @todo move to service (validate first)
     */
    protected function create($entity) {
        $this->em->persist($entity);
    }
}