<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

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
        ),
        'invokables' => array (
            'BetEntity'   => 'Bet\Entity\Bet',
            'BetForm'     => 'Bet\Form\EntryForm'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../../Application/view/layout/layout.phtml',
            'bet/index/index' => __DIR__ . '/../view/bet/index/index.phtml',
            'bet/index/add' => __DIR__ . '/../view/bet/index/add.phtml',
            'bet/index/edit' => __DIR__ . '/../view/bet/index/edit.phtml',
            'bet/index/delete' => __DIR__ . '/../view/bet/index/delete.phtml',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'bet_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '../../src/Bet/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Bet\Entity' => 'bet_entities'
                )
            )
        )
    )


);
