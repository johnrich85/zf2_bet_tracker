<?php namespace Bet\Hydrator\Factory;

use Bet\Hydrator\BetBetLinesHydrator;
use Bet\Hydrator\BetLineHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BetBetLinesHydratorFactory
 * @package Bet\Hydrator\Factory
 */
class BetBetLinesHydratorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return BetLineHydrator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $hydrator = new BetBetLinesHydrator($serviceLocator->get('BetLineHydrator'));

        return $hydrator;
    }
}