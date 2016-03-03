<?php namespace Scraper\Scraper\Facade;

use Scraper\Casters\Contract\Caster;
use Scraper\Entity\SourcePage;

/**
 * Class GuzzleScraper
 *
 * @package Scraper\Scraper\Facade
 */
class GuzzleScraper
{
    /**
     * @var \Scraper\Scraper\GuzzleScraper
     */
    protected $scraper;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    /**
     * @var SourcePage
     */
    protected $page;

    /**
     * @param $sm
     */
    public function __construct($sm)
    {
        $this->sm = $sm;
    }

    /**
     * Scrapes page and returns data.
     *
     * @param \Scraper\Entity\SourcePage $page
     * @return array|bool
     */
    public function fetch(SourcePage $page)
    {
        $this->page = $page;

        $this->scraper = new \Scraper\Scraper\GuzzleScraper($page);
        $this->scraper->connect();

        $response = $this->scraper->get();

        $caster = $this->getCaster();

        $parser = $this->getParser($response, $caster);

        return $parser->parse();
    }

    /**
     * Resolve caster from class name.
     *
     * @return Caster
     */
    public function getCaster()
    {
        return $this->sm->get($this->page->getCaster());
    }

    /**
     * Resolve parser from class name.
     *
     * @param $response
     * @param $caster
     * @return GosuLoLParser
     */
    public function getParser($response, $caster)
    {
        $parser = $this->page->getParser();

        return new $parser($response, $caster);
    }
}