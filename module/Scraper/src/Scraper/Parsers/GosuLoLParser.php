<?php namespace Scraper\Parsers;

use Psr\Http\Message\ResponseInterface;
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
     * @var DateTime
     */
    protected $start_time;

    /**
     * GosuLoLParser constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {
        $this->response = $response;
        $this->start_time = new DateTime();
    }

    /**
     * @return array|bool
     */
    public function parse() {
        $crawler = new Crawler($this->getContent());

        $filter = $crawler
            ->filter('#col1 .box')
            ->eq(1)
            ->filter('table.simple');

        $rows = $filter->filter('tr');

        if($rows->count() == 0) {
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
    protected function getMatches($rows) {
        $payload = array();

        if(iterator_count($rows) == 0) {
            return $payload;
        }

        $sport = 1;

        foreach($rows as $i=>$content) {
            $crawler = new Crawler($content);

            $opponent1 = $this->getFirstText($crawler, '.opp1 span');
            $opponent2 = $this->getLastText($crawler, '.opp2 span');
            $date = $this->getLiveInTimer($crawler);
            $event = $this->getEventHref($crawler);

            $match = compact('opponent1', 'opponent2', 'date', 'event','sport');

            $payload[] = $match;
        }

        return $payload;
    }

    /**
     * @param $crawler
     * @param $selector
     * @return mixed
     */
    protected function getLastText($crawler, $selector) {
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
    protected function getFirstText($crawler, $selector) {
        $text = $crawler->filter($selector)
            ->first()
            ->text();

        return trim($text);
    }

    /**
     * @param $crawler
     * @return mixed
     */
    protected function getEventHref($crawler) {
        return $crawler->filter('.tournament a')
            ->first()
            ->attr('href');
    }

    /**
     * @param $crawler
     * @return mixed
     */
    protected function getLiveInTimer($crawler) {
        $date = $crawler->filter('.live-in')
            ->text();

        return trim($date);
    }

    /**
     * @return string
     */
    protected function getContent() {
        $body = $this->response->getBody();

        return $body->getContents();
    }
}