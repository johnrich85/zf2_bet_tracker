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
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/scraper[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'scraperController',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'scrape' => array(
                    'options' => array(
                        'route'    => 'scrape matches',
                        'defaults' => array(
                            'controller' => 'CommandController',
                            'action'     => 'scrapeMatches'
                        )
                    )
                )
            )
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'GuzzleScraper'      => 'Scraper\Scraper\GuzzleScraper',
            'GosuLoLCaster'      => 'Scraper\Casters\Factory\GosuLoLFactory',
            'GosuMatchCaster'    => 'Scraper\Casters\Factory\GosuMatchCaster',
            'ScraperService'     => 'Scraper\Service\ScraperFactory',
        ),
        'invokables' => array(
            'ScrapedHandler'     => 'Scraper\Events\Base'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'scraper/index/index' => __DIR__ . '/../view/scraper/index/index.twig',
            'scraper/index/pages' => __DIR__ . '/../view/scraper/index/pages.twig',
            'scraper/index/scrape' => __DIR__ . '/../view/scraper/index/scrape.twig',
            'scraper/connection-error' => __DIR__ . '/../view/scraper/error/connection-error.twig',
        ),
    ),
);
