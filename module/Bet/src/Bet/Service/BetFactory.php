<?php

namespace Bet\Service;

use Bet\Validator\BetValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $bankrollService = $serviceLocator
            ->get('BankrollService');

        $validator = $serviceLocator->get('BetValidator');

        $BetService = new BetService(
            $entityManager->getRepository('Bet\Entity\Bet'),
            $bankrollService,
            $validator
        );

        $BetService->setServiceManager($serviceLocator);
        $BetService->setEntityManager($entityManager);

        return $BetService;
    }
}