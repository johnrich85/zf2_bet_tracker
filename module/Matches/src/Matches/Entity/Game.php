<?php namespace Matches\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="match_game")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`match_id`",type="integer") */
    protected $match_id;

    /** @ORM\Column(name="`date`",type="datetime") */
    protected $date;

    /** @ORM\Column(name="`created_at`",type="datetime") */
    protected $created_at;

    /** @ORM\Column(name="`updated_at`",type="datetime") */
    protected $updated_at;

    /** @ORM\Column(name="`first_team_score`",type="integer") */
    protected $first_team_score;

    /** @ORM\Column(name="`second_team_score`",type="integer") */
    protected $second_team_score;

    /**
     * @ORM\ManyToOne(targetEntity="Match", inversedBy="games")
     * @ORM\JoinColumn(name="`match_id`", referencedColumnName="id")
     */
    protected $match;

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
    public function getMatchId()
    {
        return $this->match_id;
    }

    /**
     * @param mixed $match_id
     */
    public function setMatchId($match_id)
    {
        $this->match_id = $match_id;
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
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }


}