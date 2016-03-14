<?php namespace Matches\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Game implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Matches\Service\Team
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $gameService = new \Matches\Service\Game(
            $entityManager->getRepository('Matches\Entity\Game'),
            $serviceLocator->get('GameValidator')
        );

        $gameService->setServiceManager($serviceLocator);
        $gameService->setEntityManager($entityManager);

        return $gameService;
    }
}