<?php

namespace Bankroll;

return array(
    'doctrine' => array(
        'driver' => array(
            'bankroll_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Bankroll\Entity' => 'bankroll_entities'
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'BankrollService'     => 'Bankroll\Service\BankrollFactory',
        ),
    ),
);
