<?php namespace Matches\Controller;

use Application\AppClasses\Controller\TaController;
use League\Fractal\Manager;
use Matches\Service\MatchesService;
use League\Fractal\Resource\Collection;
use Matches\Transformers\MatchesByDate;
use \Matches\Transformers\Serializer\MatchesByDate as DateSerializer;
use Zend\View\Model\JsonModel;

class AjaxController extends TaController
{
    /**
     * @var MatchesService
     */
    protected $matchesService;

    /**
     * @param MatchesService $matchesService
     */
    public function __construct(MatchesService $matchesService)
    {
        $this->matchesService = $matchesService;
    }

    /**
     * @return JsonModel
     */
    public function upcomingAction()
    {
        $matches = $this->getUpcomingMatches();

        return new JsonModel($matches);
    }

    /**
     * @return mixed
     */
    protected function getUpcomingMatches()
    {
        $from = new \DateTime();

        $to = new \DateTime();
        $to->modify('+ 1 week');

        $matches = $this->matchesService
            ->allBetween($from, $to);

        $manager = new Manager();
        $manager->setSerializer(new DateSerializer());
        $resource = new Collection($matches, new MatchesByDate());

        return $manager->createData($resource)->toArray();
    }
}
