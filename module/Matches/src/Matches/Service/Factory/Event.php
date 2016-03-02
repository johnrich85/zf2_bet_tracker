<?php namespace Matches\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Event implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Event
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $matchesService = new \Matches\Service\Event(
            $entityManager->getRepository('Matches\Entity\Event')
        );

        $matchesService->setServiceManager($serviceLocator);
        $matchesService->setEntityManager($entityManager);

        return $matchesService;
    }
}