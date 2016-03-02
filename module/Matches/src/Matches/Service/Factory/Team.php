<?php namespace Matches\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Team implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Matches\Service\Team
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $matchesService = new \Matches\Service\Team(
            $entityManager->getRepository('Matches\Entity\Team'),
            $serviceLocator->get('TeamValidator')
        );

        $matchesService->setServiceManager($serviceLocator);
        $matchesService->setEntityManager($entityManager);

        return $matchesService;
    }
}