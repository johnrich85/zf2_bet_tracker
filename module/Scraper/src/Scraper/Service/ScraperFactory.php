<?php namespace Scraper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ScraperFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ScraperService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $ScraperService = new ScraperService(
            $entityManager->getRepository('Scraper\Entity\Source'),
            $entityManager->getRepository('Scraper\Entity\SourcePage')
        );

        $ScraperService->setServiceManager($serviceLocator);
        $ScraperService->setEntityManager($entityManager);

        return $ScraperService;
    }
}