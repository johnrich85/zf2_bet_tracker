<?php namespace Scraper\Casters\Factory;

use Scraper\Casters\GosuMatchCaster as Caster;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GosuMatchCaster implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $matchesService = $serviceLocator
            ->get('MatchesService');

        $teamService = $serviceLocator
            ->get('TeamService');

        $sportService = $serviceLocator
            ->get('SportService');

        $eventService = $serviceLocator
            ->get('EventService');

        $caster = new Caster(
            $matchesService,
            $teamService,
            $sportService,
            $eventService
        );

        return $caster;
    }
}