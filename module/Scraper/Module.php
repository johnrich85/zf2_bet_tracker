<?php namespace Scraper;

use Scraper\Controller\IndexController;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\Mvc\MvcEvent;

class Module implements DependencyIndicatorInterface
{
    /**
     * Check dependencies have been loaded.
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return array('Matches');
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()
            ->getEventManager()
            ->getSharedManager();

        $sm = $e->getApplication()->getServiceManager();

        $this->registerScrapedHandler($eventManager, $sm);
    }

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
                'ScraperController' => function (\Zend\Mvc\Controller\ControllerManager $cm) {

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

    /**
     * Register event handler that carries out
     * further tasks after a page has been scraped.
     *
     * @param $em
     * @param $sm
     */
    protected function registerScrapedHandler($em, $sm)
    {
        $scrapedHandler = $sm->get('ScrapedHandler');

        $em->attach(
            'Scraper\Controller\IndexController',
            'Scraper.scraped',
            array($scrapedHandler, 'handle')
        );
    }
}
