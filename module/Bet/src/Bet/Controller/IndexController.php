<?php

namespace Bet\Controller;

use Bet\Entity\Bet;
use Application\AppClasses\Controller\TaController;
use Zend\View\Model\ViewModel;
use Bet\Form\EntryForm;

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
     * @param $betService
     * @param $bankrollService
     * @param $messageBag
     */
    public function __construct(\Bet\Service\BetService $betService,
                                \Bankroll\Service\BankrollService $bankrollService,
                                \Illuminate\Support\MessageBag $messageBag) {

        $this->betService = $betService;
        $this->bankrollService = $bankrollService;
        $this->messageBag = $messageBag;
    }

    /**
     * Lists bets.
     *
     * @todo create users & add ACL.
     * @return ViewModel
     */
    public function indexAction()
    {
        $pageNum = $this->params()->fromQuery('p');
        $params = array();

        $successfulFilter = $this->params()->fromQuery('successful');
        if($successfulFilter)
        {
            $params['successful'] = $successfulFilter;
        }

        $bets = $this->betService->getPaginatedList($pageNum,$params);

        return $this->fetchView(array(
            "bets" => $bets,
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
    public function addAction() {
        $form = $this->betService->getEntryForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $result = $this->betService
                ->create($request->getPost());

            if ($result) {
                return $this->redirect()->toRoute('bet');
            }
        }

        $view = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Create a new bet'
            )
        );

        $view->setTemplate('bet/index/update');

        return $view;

    }

    /**
     * Update bets.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction() {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form = $this->betService->getEntryForm();

            $result = $this->betService
                ->update($request->getPost());

            if ($result) {
                $this->messageBag->add('info','Great Success! Bet updated...');
            }
        }
        else {
            $form = $this->betService
                ->getEntryForm($this->params('id'));
        }

        $form->editMode();
        $form->prepare();

        $view = new ViewModel(
            array(
                'form' => $form,
                'title' => 'Update bet'
            )
        );

        $view->setTemplate('bet/index/update');

        return $view;
    }

    /**
     * Remove bets.
     *
     * @todo implement service method, add to ui.
     * @return \Zend\Http\Response
     */
    public function deleteAction() {
        $request = $this->getRequest();
        $id = $this->params('id');

        $form = $this->betService->getDeleteForm($id);

        if(!$id) {
            return $this->notFoundAction();
        }
        elseif(!$form) {
            return $this->redirect()
                ->toRoute('bet');
        }

        if ( $request->isPost() ) {
            //TODO
        }

        return $this->fetchView(array('form' => $form));
    }
}
