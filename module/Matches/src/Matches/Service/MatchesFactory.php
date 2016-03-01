<?php namespace Matches\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchesFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MatchesService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $matchesService = new MatchesService(
            $entityManager->getRepository('Matches\Entity\Match'),
            $serviceLocator->get('MatchValidator')
        );

        $matchesService->setServiceManager($serviceLocator);
        $matchesService->setEntityManager($entityManager);

        return $matchesService;
    }
}