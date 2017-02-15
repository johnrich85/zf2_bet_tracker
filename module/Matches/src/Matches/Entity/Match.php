<?php namespace Matches\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="match_match")
 * @ORM\Entity(repositoryClass="Matches\Repository\MatchRepository")
 */
class Match
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`date`",type="datetime") */
    protected $date;

    /** @ORM\Column(name="`created_at`",type="datetime") */
    protected $created_at;

    /** @ORM\Column(name="`updated_at`",type="datetime") */
    protected $updated_at;

    /**
     * @ORM\Column(name="`first_team_id`",type="integer")
     */
    protected $first_team_id;

    /**
     * @ORM\Column(name="`second_team_id`",type="integer")
     */
    protected $second_team_id;

    /** @ORM\Column(name="`sport_id`",type="integer") */
    protected $sport_id;

    /** @ORM\Column(name="`event_id`",type="integer") */
    protected $event_id;

    /** @ORM\Column(name="`hash`", type="string", nullable=true) */
    protected $hash;

    /** @ORM\Column(name="`match_source`", type="string", nullable=true) */
    protected $match_source;

    /** @ORM\Column(name="`winner`",type="integer", nullable=true) */
    protected $winner;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="matches_home")
     * @ORM\JoinColumn(name="`first_team_id`", referencedColumnName="id")
     */
    protected $first_team;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="matches_away")
     * @ORM\JoinColumn(name="`second_team_id`", referencedColumnName="id")
     */
    protected $second_team;

    /**
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="matches")
     * @ORM\JoinColumn(name="`sport_id`", referencedColumnName="id")
     */
    protected $sport;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="matches")
     * @ORM\JoinColumn(name="`event_id`", referencedColumnName="id")
     */
    protected $event;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="match")
     */
    protected $games;

    /**
     * Match constructor.
     */
    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return Team
     */
    public function getFirstTeam()
    {
        return $this->first_team;
    }

    /**
     * @param mixed $first_team
     */
    public function setFirstTeam($first_team)
    {
        $this->first_team = $first_team;
    }

    /**
     * @return Team
     */
    public function getSecondTeam()
    {
        return $this->second_team;
    }

    /**
     * @param mixed $second_team
     */
    public function setSecondTeam($second_team)
    {
        $this->second_team = $second_team;
    }

    /**
     * @return mixed
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * @param mixed $sport
     */
    public function setSport($sport)
    {
        $this->sport = $sport;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getSecondTeamScore()
    {
        return $this->second_team_score;
    }

    /**
     * @param mixed $second_team_score
     */
    public function setSecondTeamScore($second_team_score)
    {
        $this->second_team_score = $second_team_score;
    }

    /**
     * @return mixed
     */
    public function getFirstTeamScore()
    {
        return $this->first_team_score;
    }

    /**
     * @param mixed $first_team_score
     */
    public function setFirstTeamScore($first_team_score)
    {
        $this->first_team_score = $first_team_score;
    }

    /**
     * @return mixed
     */
    public function getMatchSource()
    {
        return $this->match_source;
    }

    /**
     * @param mixed $match_source
     */
    public function setMatchSource($match_source)
    {
        $this->match_source = $match_source;
    }

    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @param $data
     */
    public function populate($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Generates a hash which can be used
     * to identify the match.
     *
     * @return string
     */
    public function toHash()
    {
        return md5($this->match_source);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $name = $this->first_team->getName() . " vs " . $this->second_team->getName();

        return $name;
    }
}