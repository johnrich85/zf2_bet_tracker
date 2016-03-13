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

        $this->scraper = $this->fetchScraper();

        $response = $this->scraper->get();

        $parser = $this->getParser($response);

        return $parser->parse();
    }

    /**
     * @return \Scraper\Scraper\GuzzleScraper
     */
    protected function fetchScraper() {
        $scraper = new \Scraper\Scraper\GuzzleScraper($this->page);
        $scraper->connect();

        return $scraper;
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
     * @return mixed
     */
    public function getParser($response)
    {
        $parser = $this->page->getParser();

        return new $parser($response, $this->getCaster());
    }
}