<?php

namespace Bet\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        $BetService = new BetService(
            $entityManager->getRepository('Bet\Entity\Bet'),
            $entityManager->getRepository('Bankroll\Entity\Bankroll')
        );
        $BetService->setServiceManager($serviceLocator);
        $BetService->setEntityManager($entityManager);

        return $BetService;
    }
}