<?php

namespace Scraper;

return array(
    'doctrine' => array(
        'driver' => array(
            'scraper_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Scraper\Entity' => 'scraper_entities'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'scraper' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/scraper',
                    'defaults' => array(
                        'controller' => 'ScraperController',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'GuzzleScraper'     => 'Scraper\Scraper\GuzzleScraper',
            'GosuLoLCaster'     => 'Scraper\Casters\Factory\GosuLoLFactory'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'scraper/index/index' => __DIR__ . '/../view/scraper/index/index.phtml',
        ),
    ),
);
