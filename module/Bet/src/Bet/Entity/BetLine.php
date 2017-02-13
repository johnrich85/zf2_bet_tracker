<?php

namespace Bet\Entity;

use Bet\Hydrator\BetLineHydrator;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @ORM\Entity
 * @ORM\Table(name="bet_line")
 */
class BetLine implements Arrayable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`name`", type="string") */
    protected $name;

    /** @ORM\Column(name="`selection`", type="string") */
    protected $selection;

    /** @ORM\Column(name="`odds`", type="float") */
    protected $odds;

    /** @ORM\Column(name="`win`", type="integer") */
    protected $win;

    /**
     * @ORM\ManyToOne(targetEntity="\Matches\Entity\Match")
     * @ORM\JoinColumn(name="`match_id`", referencedColumnName="id", nullable=true)
     */
    protected $match;

    /**
     * @ORM\ManyToOne(targetEntity="\Bet\Entity\Bet", inversedBy="lines")
     * @ORM\JoinColumn(name="`bet_id`", referencedColumnName="id")
     */
    protected $bet;

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
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * @param mixed $selection
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;
    }

    /**
     * @return mixed
     */
    public function getOdds()
    {
        return $this->odds;
    }

    /**
     * @param mixed $odds
     */
    public function setOdds($odds)
    {
        $this->odds = $odds;
    }

    /**
     * @return mixed
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * @param mixed $win
     */
    public function setWin($win)
    {
        $this->win = $win;
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

    /**
     * @return mixed
     */
    public function getBet()
    {
        return $this->bet;
    }

    /**
     * @param mixed $bet
     */
    public function setBet($bet)
    {
        $this->bet = $bet;
    }

    /**
     * @param $data
     */
    public function populate($data)
    {
        $hydrator = new BetLineHydrator();

        $hydrator->hydrate($data, $this);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $payload = get_object_vars($this);

        return $payload;
    }
}