<?php namespace Matches;

use Illuminate\Support\MessageBag;
use Matches\Validator\Game;
use Matches\Validator\Match;
use Matches\Validator\Team;
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

    'router' => array(
        'routes' => array(
            'matches' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/matches/ajax[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'matchesAjaxController',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'MatchesService'     => 'Matches\Service\Factory\Matches',
            'TeamService'     => 'Matches\Service\Factory\Team',
            'SportService'     => 'Matches\Service\Factory\Sport',
            'EventService'     => 'Matches\Service\Factory\Event',
            'GameService'     => 'Matches\Service\Factory\Game',

            'MatchValidator'     => function ($sm) {
                $inputFilter = new InputFilter();
                $messageBag = new MessageBag();

                return new Match($inputFilter, $messageBag);
            },
            'TeamValidator'     => function ($sm) {
                $inputFilter = new InputFilter();
                $messageBag = new MessageBag();

                return new Team($inputFilter, $messageBag);
            },
            'GameValidator'     => function ($sm) {
                $inputFilter = new InputFilter();
                $messageBag = new MessageBag();

                return new Game($inputFilter, $messageBag);
            }
        ),
    ),
);
