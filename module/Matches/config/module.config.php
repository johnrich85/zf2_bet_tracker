<?php namespace Matches;

use Illuminate\Support\MessageBag;
use Matches\Validator\Match;
use Zend\InputFilter\InputFilter;

return array(
    'doctrine' => array(
        'driver' => array(
            'matches_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Matches\Entity' => 'matches_entities'
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'MatchesService'     => 'Matches\Service\MatchesFactory',
            'MatchValidator'     => function($sm) {
                $inputFilter = new InputFilter();
                $messageBag = new MessageBag();

                return new Match($inputFilter, $messageBag);
            }
        ),
    ),
);
