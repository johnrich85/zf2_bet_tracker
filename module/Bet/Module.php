<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bet;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'betController' => function (\Zend\Mvc\Controller\ControllerManager $cm) {
                    $betService = $cm->getServiceLocator()
                        ->get('BetService');

                    $bankrollService = $cm->getServiceLocator()
                        ->get('BankrollService');

                    $matchesService = $cm->getServiceLocator()
                        ->get('MatchesService');

                    $messageBag = $cm->getServiceLocator()
                        ->get('MessageBag');

                    $hydrator = $cm->getServiceLocator()
                        ->get('BetHydrator');

                    return new Controller\IndexController(
                        $betService,
                        $bankrollService,
                        $matchesService,
                        $messageBag,
                        $hydrator
                    );
                },
            ),
        );
    }
}
