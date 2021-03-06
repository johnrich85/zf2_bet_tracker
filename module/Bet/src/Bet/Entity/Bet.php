<?php

namespace Bet\Entity;

use Bet\Hydrator\BetHydrator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Support\Arrayable;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Bet\Repository\BetRepository")
 */
class Bet implements Arrayable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="`userId`",type="integer") */
    protected $userId;

    /** @ORM\Column(name="`name`", type="string") */
    protected $name;

    /** @ORM\Column(name="`date`",type="string") */
    protected $date;

    /** @ORM\Column(name="`amount`",type="float") */
    protected $amount;

    /** @ORM\Column(name="`return`",type="float") */
    protected $return;

    /** @ORM\Column(name="`calculated_profit`",type="float") */
    protected $calculated_profit;

    /** @ORM\Column(name="`successful`",type="integer") */
    protected $successful;

    /**
     * @ORM\OneToMany(targetEntity="\Bet\Entity\BetLine", mappedBy="bet", cascade={"persist", "remove"})
     */
    protected $lines;

    /**
     * Bet constructor.
     */
    public function __construct()
    {
        $this->lines = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param mixed $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * @param BetLine $line
     */
    public function addLine(BetLine $line)
    {
        $this->lines->add($line);
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
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
    public function getDate()
    {
        return $this->date;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param mixed $successful
     */
    public function setSuccessful($successful)
    {
        $this->successful = $successful;
    }

    /**
     * @return mixed
     */
    public function getSuccessful()
    {
        return $this->successful;
    }

    /**
     * @return mixed
     */
    public function getCalculatedProfit()
    {
        return $this->calculated_profit;
    }

    /**
     * @param mixed $calculated_profit
     */
    public function setCalculatedProfit()
    {
        if($this->successful) {
            $this->calculated_profit = $this->calculateNetProfit();
        }
        else {
            $this->calculated_profit = $this->amount * -1;
        }
    }

    /**
     * Returns either the profit or loss for a given bet.
     *
     * @return double
     * @throws \Exception
     */
    public function calculateNetProfit()
    {
        if ($this->successful === null ||
            $this->amount === null ||
            $this->return === null
        ) {
            $message = 'Unable to calculate the return, bet is not fully populated.';

            Throw new \Exception($message);
        }

        if ($this->successful != 1) {
            return $this->amount * -1;
        }

        return $this->return - $this->amount;
    }

    /**
     * Returns the difference between $value
     * and the current P/L
     * @param $value
     * @return float
     */
    public function calculateProfitDifference($value)
    {
        return $this->calculateNetProfit() - $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $payload = get_object_vars($this);

        $payload['lines'] = $payload['lines']->toArray();

        return $payload;
    }
}