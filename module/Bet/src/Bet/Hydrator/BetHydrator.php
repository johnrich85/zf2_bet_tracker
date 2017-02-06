<?php namespace Bet\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class BetHydrator
 *
 * @package Bet\Hydrator
 */
class BetHydrator extends ClassMethods
{
    /**
     * @var BetBetLinesHydrator
     */
    protected $linesHydrator;

    /**
     * BetHydrator constructor.
     *
     * @param bool $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);

        $this->linesHydrator = new BetBetLinesHydrator();

        $this->addStrategy('lines', $this->linesHydrator);
    }

    /**
     * @param object $object
     * @return array
     */
    public function extract($object)
    {
        return parent::extract($object); // TODO: Change the autogenerated stub
    }

    /**
     * @param array $data
     * @param object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $this->linesHydrator->setContext($object);

        return parent::hydrate($data, $object);
    }

}