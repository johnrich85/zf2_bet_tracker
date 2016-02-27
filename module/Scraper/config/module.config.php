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
    'service_manager' => array(
        'factories' => array(
            'GuzzleScraper'     => 'Scraper\Scraper\GuzzleScraper',
        ),
    ),
);
