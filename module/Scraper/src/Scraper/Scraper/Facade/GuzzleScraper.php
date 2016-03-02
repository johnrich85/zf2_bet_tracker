<?php namespace Scraper\Scraper\Facade;

use Scraper\Casters\Contract\Caster;
use Scraper\Parsers\GosuLoLParser;

/**
 * Created by PhpStorm.
 * User: John
 * Date: 02-Mar-16
 * Time: 9:45 PM
 */
class GuzzleScraper
{
    /**
     * @var \Scraper\Scraper\GuzzleScraper
     */
    protected $scraper;

    /**
     * @var
     */
    protected $sm;

    /**
     * GuzzleScraper constructor.
     * @param \Scraper\Entity\SourcePage $page
     */
    public function __construct($sm)
    {
        $this->sm = $sm;
    }

    /**
     * @param \Scraper\Entity\SourcePage $page
     * @return array|bool
     */
    public function fetch(\Scraper\Entity\SourcePage $page) {
        $this->scraper = new \Scraper\Scraper\GuzzleScraper($page);
        $this->scraper->connect();

        $response = $this->scraper->get();

        $caster = $this->getCaster();

        $parser = $this->getParser($response, $caster);

        return $parser->parse();
    }

    /**
     * @return Caster
     * @todo
     */
    public function getCaster()
    {
        return $this->sm->get('GosuLoLCaster');
    }

    /**
     * @param $response
     * @param $caster
     * @return GosuLoLParser
     * @todo
     */
    public function getParser($response, $caster) {
        return new GosuLoLParser($response, $caster);
    }
}