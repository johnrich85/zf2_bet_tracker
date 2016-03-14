<?php namespace Scraper\Casters;

use Matches\Entity\Game;
use Matches\Entity\Match;
use Matches\Service\Event;
use Matches\Service\MatchesService;
use Matches\Service\Game as GameService;
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
     * @var Game
     */
    protected $gameService;

    /**
     * @var Event
     */
    protected $eventService;

    /**
     * @var array
     */
    protected $entities;

    /**
     * GosuMatchCaster constructor.
     *
     * @param MatchesService $matchesService
     * @param Team $teamService
     * @param Game $gameService
     * @param Event $eventService
     */
    public function __construct(
        MatchesService $matchesService,
        Team $teamService,
        GameService $gameService,
        Event $eventService
    ) {
        $this->matchesService = $matchesService;
        $this->teamService = $teamService;
        $this->gameService = $gameService;
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

        $match->getFirstTeam()
            ->setName($data['first_team_name']);

        $match->getSecondTeam()
            ->setName($data['second_team_name']);

        $match->setDate($this->getDate($data));

        $match->setWinner($this->getWinner($match, $data));

        $this->addGames($match, $data);

        $this->entities[] = $match;
    }

    /**
     * @param Match $match
     * @param array $data
     */
    protected function addGames(Match $match, array $data) {
        $first_score = $data['first_team_score'];
        $second_score = $data['first_team_score'];

        $games = $this->createGames($match,$first_score, 1, 0);
        $games += $this->createGames($match,$second_score, 0, 1);

        foreach($games as $game) {
            $match->getGames()->add($game);
        }
    }

    /**
     * @param Match $match
     * @param $numGames
     * @param $score1
     * @param $score2
     * @return array
     */
    protected function createGames(Match $match, $numGames, $score1, $score2) {
        $payload = [];

        $date = new \DateTime();

        $data = [
            'created_at' => $date,
            'updated_at' => $date,
            'date' => $match->getDate(),
            'match' => $match,
            'first_team_score' => $score1,
            'second_team_score' => $score2
        ];

        for($a = 0; $a < $numGames; $a++) {
            $game = $this->gameService->create($data);

            $payload[] = $game;
        }

        return $payload;
    }

    /**
     * @param Match $match
     * @param array $data
     * @return int|mixed
     */
    protected function getWinner(Match $match, array $data) {
        $first_score = $data['first_team_score'];
        $second_score = $data['first_team_score'];

        if($first_score > $second_score) {
            $id = $match->getFirstTeam()->getId();
        }
        elseif($second_score > $first_score) {
            $id = $match->getSecondTeam()->getId();
        }
        else {
            $id = 0;
        }

        return $id;
    }

    /**
     * Returns DateTime converted from CET to
     * GMT.
     *
     * @param $data
     * @return \DateTime
     */
    protected function getDate($data) {
        $date = new \DateTime(
            $data['date'],
            new \DateTimeZone('Europe/Berlin')
        );

        $date->setTimezone(
            new \DateTimeZone('Europe/London')
        );

        return $date;
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