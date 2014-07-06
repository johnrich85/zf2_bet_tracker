<?php

namespace Bet\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $BetService = new BetService();
        $BetService->setServiceManager($serviceLocator);
        $BetService->setEntityManager($serviceLocator->get('doctrine.entitymanager.orm_default'));

        return $BetService;
    }
}