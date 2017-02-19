<?php namespace Bet;

use Bet\Hydrator\BetBetLinesHydrator;
use Bet\Hydrator\BetHydrator;
use Bet\Hydrator\BetLineHydrator;
use Bet\Hydrator\Factory\BetBetHydratorFactory;
use Bet\Hydrator\Factory\BetBetLinesHydratorFactory;
use Bet\Hydrator\Factory\BetLineHydratorFactory;
use Bet\Validator\BetValidatorFactory;

return array(
    'router' => array(
        'routes' => array(
            'bet' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/bet[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'betController',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'BetService'     => 'Bet\Service\BetFactory',
            'BetValidator'   => BetValidatorFactory::class,
            'BetHydrator'   => BetBetHydratorFactory::class,
            'BetLineHydrator'   => BetLineHydratorFactory::class,
            'BetBetLinesHydrator'   => BetBetLinesHydratorFactory::class,
        ),
        'invokables' => array (
            'BetEntity'   => 'Bet\Entity\Bet',
            'BetEntryForm'     => 'Bet\Form\EntryForm',
            'BetDeleteForm'     => 'Bet\Form\DeleteForm',
            'MessageBag'  => 'Illuminate\Support\MessageBag'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../../Application/view/layout/layout.phtml',
            'bet/index/index' => __DIR__ . '/../view/bet/index/index.twig',
            'bet/index/update' => __DIR__ . '/../view/bet/index/update.twig',
            'bet/index/delete' => __DIR__ . '/../view/bet/index/delete.twig',
            'partials/pagination'     => __DIR__ . '/../view/partials/pagination.twig',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'bet_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Bet\Entity' => 'bet_entities'
                )
            )
        )
    )
);
