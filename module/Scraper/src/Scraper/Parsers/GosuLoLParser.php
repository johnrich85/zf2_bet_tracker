<?php namespace Scraper\Parsers;

use Psr\Http\Message\ResponseInterface;
use Scraper\Parsers\Contract\Parser;
use Symfony\Component\DomCrawler\Crawler;

class GosuLoLParser implements Parser
{

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * GosuLoLParser constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function parse() {
        $crawler = new Crawler($this->getContent());

        $filter = $crawler
            ->filter('table.simple')
            ->eq(1);

        $rows = $filter->filter('tr');

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

        foreach($rows as $i=>$content) {
            $crawler = new Crawler($content);

            $opponent1 = $crawler->filter('.opp1 span')
                ->first()
                ->text();

            $opponent2 = $crawler->filter('.opp2 span')
                ->last()
                ->text();

            $payload[] = array(
                'opponent_1' => $opponent1,
                'opponent_2' => $opponent2,
            );
        }

        return $payload;
    }

    /**
     * @return string
     */
    protected function getContent() {
        $body = $this->response->getBody();

        return $body->getContents();
    }
}