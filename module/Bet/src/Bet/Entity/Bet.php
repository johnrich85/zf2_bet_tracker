<?php

namespace Bet\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Bet\Repository\BetRepository")
 */

class Bet implements InputFilterAwareInterface {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    //TODO - need to implement users :D
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

    /** @ORM\Column(name="`successful`",type="integer") */
    protected $successful;

    protected $inputFilter;

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
     * Returns either the profit or loss for a given bet.
     *
     * @return double
     * @throws \Exception
     */
    public function calculateProfitOrLoss()
    {
        if($this->successful == null || $this->amount == null || $this->return == null)
        {
            Throw new \Exception('Unable to calculate the return, bet is not fully populated.');
        }

        if ($this->successful != 1)
        {
            return $this->amount * -1;
        }

        return $this->return - $this->amount;
    }


    //Todo: Form related methods - need a better way of doing this
    public function exchangeArray($data)
    {
        var_dump($data);
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->userId     = (isset($data['userId']))     ? $data['userId']     : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->amount = (isset($data['amount'])) ? $data['amount'] : null;
        $this->return = (isset($data['return'])) ? $data['return'] : null;
        $this->successful = (isset($data['successful'])) ? $data['successful'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'date',
                'required' => true,
                'filters' => array(
                    array(
                        'name'      => 'DateTimeFormatter',
                        'options'   => array(
                            'format' => 'Y-m-d',
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'amount',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Between',
                        'options' => array(
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'return',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Between',
                        'options' => array(
                            'min'      => 0,
                            'max'      => 10000,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;

        }

        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}