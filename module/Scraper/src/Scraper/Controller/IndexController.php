<?php namespace Scraper\Controller;

use Application\AppClasses\Controller\TaController;
use Matches\Entity\Match;
use Matches\Service\MatchesService;
use Scraper\Casters\GosuLoLCaster;
use Scraper\Parsers\GosuLoLParser;
use Scraper\Repository\SourcePageRepository;
use Scraper\Scraper\GuzzleScraper;
use Scraper\Service\ScraperService;

class IndexController extends TaController
{
    /**
     * @var SourcePageRepository
     */
    protected $sourcePageRepo;

    /**
     * @var MatchesService
     */
    protected $scraperService;

    /**
     * @param SourcePageRepository $repo
     * @param ScraperService $scraperService
     */
    public function __construct(SourcePageRepository $repo,ScraperService $scraperService) {
        $this->sourcePageRepo = $repo;
        $this->scraperService = $scraperService;
    }

    /**
     *
     */
    public function indexAction()
    {
        $page = $this->sourcePageRepo
            ->eagerFind(1);

        try{
            $scraper = new GuzzleScraper($page);
            $scraper->connect();
            $response = $scraper->get();
        }
        catch(\Exception $e) {
            echo $e->getMessage();
            die();
        }

        $caster = $this->getServiceLocator()
            ->get('GosuLoLCaster');

        $parser = new GosuLoLParser($response, $caster);

        $data = $parser->parse();

        if($data) {
            $this->scraperService->persistEntities($data);
        }
        else {
            echo "Parser returned no data";
        }
    }
}
