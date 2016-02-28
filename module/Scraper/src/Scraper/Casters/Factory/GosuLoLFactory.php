<?php namespace Scraper\Casters\Factory;

use Scraper\Casters\GosuLoLCaster;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GosuLoLFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator
            ->get('doctrine.entitymanager.orm_default');

        $caster = new GosuLoLCaster(
            $entityManager
        );

        return $caster;
    }
}