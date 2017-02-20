<?php namespace Scraper\Parsers;

use Psr\Http\Message\ResponseInterface;
use Scraper\Casters\Contract\Caster;
use Scraper\Parsers\Contract\Parser;
use Symfony\Component\DomCrawler\Crawler;
use Zend\Form\Element\DateTime;

class GosuMatchParser implements Parser
{

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var
     */
    protected $entityCaster;

    /**
     * @var
     */
    protected $data = [];

    /**
     * @param ResponseInterface $response
     * @param Caster $entityCaster
     */
    public function __construct(ResponseInterface $response, Caster $entityCaster)
    {
        $this->response = $response;
        $this->entityCaster = $entityCaster;
    }

    /**
     * @return array|bool
     */
    public function parse()
    {
        $crawler = new Crawler($this->getContent());

        $this->data += $this->parseScore($crawler);
        $this->data['date'] = $this->parseDate($crawler);
        $this->data['first_team_name'] = $this->parseName(1, $crawler);
        $this->data['second_team_name'] = $this->parseName(2, $crawler);

        $this->data['match_source'] = $this->response->getHeaderLine('REQUEST_URI');

        $this->entityCaster->cast($this->data);

        return $this->entityCaster->getEntities();
    }

    /**
     * @param $crawler
     * @return array
     */
    protected function parseScore($crawler)
    {
        $payload = [];

        $results = $crawler
            ->filter('div.score span');

        $payload['first_team_score'] = $results->eq(0)
            ->text();

        $payload['second_team_score'] = $results->eq(1)
            ->text();

        return $payload;
    }

    /**
     * @param $crawler
     * @return string
     */
    protected function parseDate($crawler) {
        $date = $crawler->filter('.match  .details small')
            ->first()
            ->text();

        return trim($date);
    }

    /**
     * @param $id
     * @param $crawler
     * @return string
     */
    protected function parseName($id, $crawler) {
        $selector = ".match  .team.team-$id a";

        $date = $crawler->filter($selector)
            ->first()
            ->text();

        return trim($date);
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $body = $this->response->getBody();

        return $body->getContents();
    }
}