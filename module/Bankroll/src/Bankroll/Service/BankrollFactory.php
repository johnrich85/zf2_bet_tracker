<?php

namespace Bankroll\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BankrollFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $BankrollService = new BankrollService();
        $BankrollService->setServiceManager($serviceLocator);
        $BankrollService->setEntityManager($serviceLocator->get('doctrine.entitymanager.orm_default'));

        return $BankrollService;
    }
}