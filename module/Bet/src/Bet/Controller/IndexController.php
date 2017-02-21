<?php namespace Bet\Controller;

use Application\AppClasses\Controller\TaController;
use Bet\Entity\Bet;
use Bet\Entity\BetLine;
use Bet\Hydrator\BetHydrator;
use \Bet\Service\BetService;
use \Bankroll\Service\BankrollService;
use \Illuminate\Support\MessageBag;
use \League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use \Matches\Service\MatchesService;
use Matches\Transformers\Serializer\MatchesByDate;
use Particle\Validator\Rule\NotEmpty;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 *
 * @package Bet\Controller
 */
class IndexController extends TaController
{
    /**
     * @var \Bet\Service\BetService
     */
    protected $betService;

    /**
     * @var \Bankroll\Service\BankrollService
     */
    protected $bankrollService;

    /**
     * @var MatchesService
     */
    protected $matchesService;

    /**
     * @var BetHydrator
     */
    protected $betHydrator;

    /**
     * IndexController constructor.
     *
     * @param BetService $betService
     * @param BankrollService $bankrollService
     * @param MatchesService $matchesService
     * @param MessageBag $messageBag
     * @param BetHydrator $betHydrator
     */
    public function __construct(
        BetService $betService,
        BankrollService $bankrollService,
        MatchesService $matchesService,
        MessageBag $messageBag,
        BetHydrator $betHydrator
    ) {

        $this->betService = $betService;
        $this->bankrollService = $bankrollService;
        $this->matchesService = $matchesService;
        $this->messageBag = $messageBag;
        $this->betHydrator = $betHydrator;
    }

    /**
     * Lists bets.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $pageNum = $this->params()->fromQuery('p');

        $params = array();

        $successfulFilter = $this->params()->fromQuery('successful');
        if ($successfulFilter) {
            $params['successful'] = $successfulFilter;
        }

        $bets = $this->betService->getPaginatedList($pageNum, $params);
        $betsByDay = $this->betService->getBetsByDay(1, 5, $params);
        $betsByMonth = $this->betService->getBetsByMonth(1, 5, $params);

        return $this->fetchView(array(
            "bets" => $bets,
            "betsByDay" => $betsByDay,
            "betsByMonth" => $betsByMonth,
            "winning" => $this->betService->getBetCount(1),
            "losing" => $this->betService->getBetCount(0),
            "bankroll" => $this->bankrollService->getById(1)
        ));
    }

    /**
     * Add bets.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $matches = $this->getUpcomingMatches();

        $params = $this->getRouteOrPost();

        $model = $this->getBetModel($params);

        $modelData = $this->transform($model);

        $viewData = [
            'bet' => json_encode($modelData),
            'title' => 'Create a new bet',
            'matches' => json_encode($matches)
        ];

        return $this->fetchView($viewData, 'bet/index/update');
    }

    /**
     * Processes post request.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function processAction()
    {
        $request = $this->getRequest();

        $data = (array) $request->getPost();

        $result = $this->betService->create($data);

        if (!$result) {
            $errors = $this->betService->getMessages();

            $this->parseErrors($errors);

            return $this->forward()->dispatch('betController', array(
                'action' => 'add',
                'data'   => $data,
            ));
        }

        return $this->redirect()->toRoute('bet');
    }


    /**
     * Update bets.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $matches = $this->getUpcomingMatches();
        $params = $this->getRouteOrPost();

        if($this->params('id')) {
            $bet = $this->betService
                ->find($this->params('id'));
        } else {
            $bet = $this->getBetModel($params);
        }

        $modelData = $this->transform($bet);

        $viewData = [
            'bet' => json_encode($modelData),
            'title' => 'Updating le bet',
            'matches' => json_encode($matches)
        ];

        return $this->fetchView($viewData, 'bet/index/update');
    }

    /**
     * Processes update action.
     *
     * @return mixed|\Zend\Http\Response
     */
    public function processUpdateAction()
    {
        $request = $this->getRequest();

        $data = (array) $request->getPost();

        $bet = $this->betService
            ->find($request->getPost('id'));

        $result = $this->betService
            ->update($bet, $request->getPost());

        if (!$result) {
            $errors = $this->betService->getMessages();

            $this->parseErrors($errors);

            return $this->forward()->dispatch('betController', array(
                'action' => 'edit',
                'data'   => $data,
            ));
        }

        return $this->redirect()->toRoute('bet');
    }

    /**
     * Remove bets.
     *
     * @todo implement
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {

    }

    /**
     * @return mixed
     */
    protected function getRouteOrPost()
    {
        $payload = $this->params()->fromRoute('data');

        if($payload == null) {
            $payload = $this->getRequest()->getPost();
        }

        return $payload;
    }

    /**
     * @param $params
     * @return Bet
     */
    protected function getBetModel($params)
    {
        $bet = new Bet();

        if(count($params)) {
            $this->betHydrator->hydrate((array) $params, $bet);
        }

        return $bet;
    }

    /**
     * @param Bet $model
     * @return array
     * @Todo: move to transformer.
     */
    protected function transform(Bet $model)
    {
        $modelData = $model->toArray();
        if(count($modelData['lines']) == 0) {
            $line = new BetLine();
            $modelData['lines'][] = $line->toArray();
        } else {
            foreach($modelData['lines'] as $key=>$line) {
                $modelData['lines'][$key] = $line->toArray();
            }
        }

        return $modelData;
    }

    /**
     * @param $errors
     * @todo: move out of controller.
     */
    protected function parseErrors($errors)
    {
        foreach($errors as $error) {
            $this->messageBag->add('error', current($error));
        }
    }

    /**
     * @return mixed
     */
    protected function getUpcomingMatches()
    {
        $from = new \DateTime();
        $from->setTime(0, 0, 0);

        $to = new \DateTime();
        $to->modify('+ 2 week');

        $matches = $this->matchesService
            ->allBetween($from, $to);

        $manager = new Manager();
        $manager->setSerializer(new MatchesByDate());
        $resource = new Collection($matches, new \Matches\Transformers\MatchesByDate());

        return $manager->createData($resource)->toArray();
    }
}
