<?php

namespace Bet\Controller;

use Application\AppClasses\Controller\TaController;
use \Bet\Service\BetService;
use \Bankroll\Service\BankrollService;
use \Illuminate\Support\MessageBag;
use \League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use \Matches\Service\MatchesService;
use Matches\Transformers\Serializer\MatchesByDate;
use Zend\View\Model\ViewModel;

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
     * @param BetService $betService
     * @param BankrollService $bankrollService
     * @param MatchesService $matchesService
     * @param MessageBag $messageBag
     */
    public function __construct(
        BetService $betService,
        BankrollService $bankrollService,
        MatchesService $matchesService,
        MessageBag $messageBag
    ) {

        $this->betService = $betService;
        $this->bankrollService = $bankrollService;
        $this->matchesService = $matchesService;
        $this->messageBag = $messageBag;
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
        $betsByDay = $this->betService->getBetsByDay($pageNum, 5, $params);

        return $this->fetchView(array(
            "bets" => $bets,
            "betsByDay" => $betsByDay,
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
        $form = $this->betService->getEntryForm();

        $request = $this->getRequest();

        $matches = $this->getUpcomingMatches();

        if ($request->isPost()) {
            $result = $this->betService
                ->create($request->getPost());

            if ($result) {
                return $this->redirect()->toRoute('bet');
            }
        }

        $viewData = [
            'theForm' => $form,
            'title' => 'Create a new bet',
            'matches' => $matches
        ];

        return $this->fetchView($viewData, 'bet/index/update');
    }

    /**
     * Update bets.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form = $this->betService->getEntryForm();

            $result = $this->betService
                ->update($request->getPost());

            if ($result) {
                $this->messageBag->add('info', 'Great Success! Bet updated...');
            } else {
                $this->messageBag->add('error', 'Fail! Bet not updated...');
            }
        } else {
            $form = $this->betService
                ->getEntryForm($this->params('id'));
        }

        $form->editMode();
        $form->prepare();

        $viewData = [
            'theForm' => $form,
            'title' => 'Update bet'
        ];

        return $this->fetchView($viewData, 'bet/index/update');
    }

    /**
     * Remove bets.
     *
     * @todo implement service method
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = $this->params('id');

        $form = $this->betService->getDeleteForm($id);

        if (!$id) {
            return $this->notFoundAction();
        } elseif (!$form) {
            return $this->redirect()->toRoute('bet');
        }

        if ($request->isPost()) {
            //TODO
        }

        $viewData = [
            'theForm' => $form
        ];

        return $this->fetchView($viewData);
    }

    /**
     * @return mixed
     */
    protected function getUpcomingMatches()
    {
        $from = new \DateTime();
        $from->setTime(0, 0, 0);

        $to = new \DateTime();
        $to->modify('+ 1 week');

        $matches = $this->matchesService
            ->allBetween($from, $to);

        $manager = new Manager();
        $manager->setSerializer(new MatchesByDate());
        $resource = new Collection($matches, new \Matches\Transformers\MatchesByDate());

        return $manager->createData($resource)->toArray();
    }
}
