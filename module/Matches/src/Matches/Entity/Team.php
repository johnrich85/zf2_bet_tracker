<?php namespace Matches\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="match_team")
 */

class Team {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`name`",type="string") */
    protected $name;

    /** @ORM\Column(name="`sport`",type="integer") */
    protected $sport;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="first_team", fetch="EAGER")
     */
    protected $matches_home;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="second_team", fetch="EAGER")
     */
    protected $matches_away;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getMatchesHome()
    {
        return $this->matches_home;
    }

    /**
     * @param mixed $matches_home
     */
    public function setMatchesHome($matches_home)
    {
        $this->matches_home = $matches_home;
    }

    /**
     * @return mixed
     */
    public function getMatchesAway()
    {
        return $this->matches_away;
    }

    /**
     * @param mixed $matches_away
     */
    public function setMatchesAway($matches_away)
    {
        $this->matches_away = $matches_away;
    }
}