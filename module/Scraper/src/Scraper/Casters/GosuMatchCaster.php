<?php namespace Scraper\Casters;

use Matches\Service\Event;
use Matches\Service\MatchesService;
use Matches\Service\Sport;
use Matches\Service\Team;
use Scraper\Casters\Contract\Caster;

class GosuMatchCaster implements Caster
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

        $this->matchesService->clearEm();
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    public function cast(array $data)
    {
        $match = $this->getMatch($data);

        //TODO: add new team names, add date, work out if w/l/d, create new game instances for each and add.
    }

    /**
     * @param array $data
     * @return \Matches\Entity\Match
     */
    protected function getMatch(array $data)
    {
        $query = array(
            'match_source' => $data['match_source']
        );

        $match = $this->matchesService
            ->findOneBy($query);

        if ($match == null) {
            $match = $this->matchesService
                ->create($data);
        }

        return $match;
    }

}