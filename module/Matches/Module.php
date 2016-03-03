<?php namespace Matches;

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
                'matchesAjaxController' => function (\Zend\Mvc\Controller\ControllerManager $cm) {
                    $matchesService = $cm->getServiceLocator()
                        ->get('MatchesService');

                    return new Controller\AjaxController($matchesService);
                },
            ),
        );
    }
}
