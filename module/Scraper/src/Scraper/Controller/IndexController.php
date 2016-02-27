<?php namespace Scraper\Controller;

use Application\AppClasses\Controller\TaController;
use Scraper\Parser\GosuLoLParser;
use Scraper\Repository\SourcePageRepository;
use Scraper\Scraper\GuzzleScraper;

class IndexController extends TaController
{
    /**
     * @var SourcePageRepository
     */
    protected $sourcePageRepo;

    public function __construct(SourcePageRepository $repo) {
        $this->sourcePageRepo = $repo;
    }

    public function indexAction()
    {
        $page = $this->sourcePageRepo
            ->eagerFind(1);

        $scraper = new GuzzleScraper($page);
        $scraper->connect();

        $response = $scraper->get();

        $parser = new GosuLoLParser($response);

        $data = $parser->parse();

    }
}
