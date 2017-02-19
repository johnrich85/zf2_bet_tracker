<?php namespace Bet\Hydrator\Factory;

use Bet\Hydrator\BetBetLinesHydrator;
use Bet\Hydrator\BetHydrator;
use Bet\Hydrator\BetLineHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BetBetHydratorFactory
 * @package Bet\Hydrator\Factory
 */
class BetBetHydratorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return BetHydrator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $hydrator = new BetHydrator(true, $serviceLocator->get('BetBetLinesHydrator'));

        return $hydrator;
    }
}