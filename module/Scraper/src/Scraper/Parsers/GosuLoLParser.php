<?php namespace Scraper\Parsers;

use Psr\Http\Message\ResponseInterface;
use Scraper\Casters\Contract\Caster;
use Scraper\Parsers\Contract\Parser;
use Symfony\Component\DomCrawler\Crawler;
use Zend\Form\Element\DateTime;

class GosuLoLParser implements Parser
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
     * @var DateTime
     */
    protected $start_time;

    /**
     * @param ResponseInterface $response
     * @param Caster $entityCaster
     */
    public function __construct(ResponseInterface $response, Caster $entityCaster)
    {
        $this->response = $response;
        $this->entityCaster = $entityCaster;
        $this->start_time = new DateTime();
    }

    /**
     * @return array|bool
     */
    public function parse()
    {
        $crawler = new Crawler($this->getContent());

        $filter = $crawler
            ->filter('#col1 .box')
            ->eq(1)
            ->filter('table.simple');

        $rows = $filter->filter('tr');

        if ($rows->count() == 0) {
            return false;
        }

        return $this->getMatches($rows);
    }

    /**
     * Gets array of upcoming matches.
     *
     * @param $rows
     * @return array
     */
    protected function getMatches($rows)
    {
        if (iterator_count($rows) == 0) {
            return array();
        }

        $sport = 1;

        foreach ($rows as $i => $content) {
            $crawler = new Crawler($content);

            $first_team = $this->getFirstText($crawler, '.opp1 span');
            $second_team = $this->getLastText($crawler, '.opp2 span');
            $date = $this->getLiveInTimer($crawler);
            $event = $this->getEventHref($crawler);

            $match = compact('first_team', 'second_team', 'date', 'event', 'sport');

            $this->entityCaster->cast($match);
        }

        return $this->entityCaster->getEntities();
    }

    /**
     * @param $crawler
     * @param $selector
     * @return mixed
     */
    protected function getLastText($crawler, $selector)
    {
        $text = $crawler->filter($selector)
            ->last()
            ->text();

        return trim($text);
    }

    /**
     * @param $crawler
     * @param $selector
     * @return mixed
     */
    protected function getFirstText($crawler, $selector)
    {
        $text = $crawler->filter($selector)
            ->first()
            ->text();

        return trim($text);
    }

    /**
     * @param $crawler
     * @return mixed
     */
    protected function getEventHref($crawler)
    {
        return $crawler->filter('.tournament a')
            ->first()
            ->attr('href');
    }

    /**
     * @param $crawler
     * @return mixed
     */
    protected function getLiveInTimer($crawler)
    {
        $date = $crawler->filter('.live-in')
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