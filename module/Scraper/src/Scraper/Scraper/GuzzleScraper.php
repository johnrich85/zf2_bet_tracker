<?php namespace Scraper\Scraper;

use Scraper\Entity\Source;
use Scraper\Entity\SourcePage;
use Scraper\Scraper\Contract\Scraper;
use \GuzzleHttp\Client;

/**
 * Class GuzzleScraper
 * @package Scraper\Scraper\Contract
 */
class GuzzleScraper implements Scraper
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Source
     */
    protected $source;

    /**
     * @var SourcePage
     */
    protected $page;

    /**
     * GuzzleScraper constructor.
     * @param SourcePage $sourcePage
     */
    public function __construct(SourcePage $sourcePage)
    {
        $this->source = $sourcePage->getSource();
        $this->page = $sourcePage;
    }

    /**
     * @return bool
     */
    public function connect()
    {
        $this->client = new Client([
            'base_uri' => $this->source->getHostname(),
            'timeout'  => 4.0,
        ]);

        return true;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get()
    {
        return $this->client->request('GET', $this->page->getUri());
    }
}