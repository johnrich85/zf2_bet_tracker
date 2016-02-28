<?php namespace Scraper\Controller;

use Application\AppClasses\Controller\TaController;
use Matches\Entity\Match;
use Matches\Service\MatchesService;
use Scraper\Casters\GosuLoLCaster;
use Scraper\Parsers\GosuLoLParser;
use Scraper\Repository\SourcePageRepository;
use Scraper\Scraper\GuzzleScraper;

class IndexController extends TaController
{
    /**
     * @var SourcePageRepository
     */
    protected $sourcePageRepo;

    /**
     * @var MatchesService
     */
    protected $matchesService;

    public function __construct(SourcePageRepository $repo, MatchesService $matchesService) {
        $this->sourcePageRepo = $repo;
        $this->matchesService = $matchesService;
    }

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

        $parser = new GosuLoLParser($response);

        $data = $parser->parse();

        if($data) {
            $caster = $this->getServiceLocator()
                ->get('GosuLoLCaster');

            $caster->cast($data);

            $entities = $caster->getEntities();

        }
        else {
            echo "Parser returned no data";
        }
    }
}
