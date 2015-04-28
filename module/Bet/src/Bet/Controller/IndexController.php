<?php

namespace Bet\Controller;

use Bet\Entity\Bet;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Bet\Form\EntryForm;

class IndexController extends AbstractActionController
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
     * @var ViewModel
     */
    protected $viewModel;

    /**
     * @param $betService
     * @param $bankrollService
     */
    public function __construct($betService, $bankrollService) {
        $this->betService = $betService;
        $this->bankrollService = $bankrollService;

        $this->viewModel = new ViewModel(array(
            'bankroll' => $this->bankrollService->getById(1)
        ));
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

        $this->viewModel->setVariables(array(
            "bets" => $bets,
            "winning" => $this->betService->getBetCount(1),
            "losing" => $this->betService->getBetCount(0)
        ));

        return $this->viewModel;

    }

    /**
     * Add bets.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction() {

        $form = $this->betService->getEntryForm();
        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $result = $this->betService->create($request->getPost());

            if ($result) {
                return $this->redirect()->toRoute('bet');
            }
        }

        return new ViewModel(array('form' => $form));

    }

    /**
     * Update bets.
     *
     * @todo fix this, add option to UI & add all form fields.
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction() {

        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $form = $this->betService->getEntryForm();
            $result = $this->betService->update($request->getPost());

            if ($result) {
                return $this->redirect()->toRoute('bet', array(
                    'controller' => 'index',
                    'action' =>  'edit',
                    'id' => $this->params('id')
                ));
            }
        }
        else {
            $form = $this->betService->getEntryForm($this->params('id'));
        }

        return new ViewModel(array('form' => $form));

    }

    /**
     * Remove bets.
     *
     * @todo implement service method, add to ui.
     * @return \Zend\Http\Response
     */
    public function deleteAction() {

        $request = $this->getRequest();

        if ( !$this->params('id') || !$request->getPost('confirm') ) {
            $this->flashMessenger()->addMessage('To delete an entry, please browse to the management page, press delete & click "ok" to confirm you wish to delete it');
            return $this->redirect()->toRoute('bet');
        }

        $result = $this->betService->delete($this->params('id'));
        $message = ($result == true ? 'Entry deleted successfully' : 'Unable to delete entry, please try again.');
        $this->flashMessenger()->addMessage($message);

        return $this->redirect()->toRoute('bet');

    }
}
