<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'doctrine' => array(
        'driver' => array(
            'bankroll_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '../../src/Bankroll/Entity')
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
