<?php

namespace Bankroll\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 */

class Bankroll {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    //TODO - need to implement users :D
    /** @ORM\Column(name="`userId`",type="integer") */
    protected $userId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /** @ORM\Column(name="`lastChange`",type="datetime") */
    protected $lastChange;

    /** @ORM\Column(name="`amount`",type="float") */
    protected $amount;

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
     * @param mixed $lastChange
     */
    public function setLastChange($lastChange)
    {
        $this->lastChange = $lastChange;
    }

    /**
     * @return mixed
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }


    public function amendAmount($value)
    {
        if (!is_numeric($value))
        {
            Throw new \Exception('Cannot amend the bankroll using a non-numeric value.');
        }

        $amendedBankRollTotal = $this->amount += $value;
        $this->setAmount($amendedBankRollTotal);

        return $this->amount;
    }

}