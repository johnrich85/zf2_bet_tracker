<?php namespace Scraper\Parser;

use Psr\Http\Message\ResponseInterface;
use Scraper\Parser\Contract\Parser;

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

    public function parse() {

        $body = $this->response->getBody();

        $content = $body->getContents();
        
    }
}