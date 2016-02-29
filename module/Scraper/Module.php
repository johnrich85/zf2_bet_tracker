<?php namespace Scraper;

use Scraper\Controller\IndexController;

class Module
{
    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'ScraperController' => function(\Zend\Mvc\Controller\ControllerManager $cm) {

                    $entityManager = $cm->getServiceLocator()
                        ->get('doctrine.entitymanager.orm_default');

                    $repo = $entityManager
                        ->getRepository('Scraper\Entity\SourcePage');

                    $scraperService = $cm->getServiceLocator()
                        ->get('ScraperService');

                    return new IndexController($repo, $scraperService);
                },
            ),
        );
    }
}
