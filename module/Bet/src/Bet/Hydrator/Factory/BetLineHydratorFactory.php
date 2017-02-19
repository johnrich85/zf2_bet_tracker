<?php namespace Bet\Hydrator\Factory;

use Bet\Hydrator\BetLineHydrator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BetLineHydratorFactory
 * @package Bet\Hydrator\Factory
 */
class BetLineHydratorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return BetLineHydrator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $betLineHydrator = new BetLineHydrator();
        $betLineHydrator->setServiceManager($serviceLocator);

        return $betLineHydrator;
    }
}