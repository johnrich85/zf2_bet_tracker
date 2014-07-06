<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bet\Controller;

use Bet\Entity\Bet;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Bet\Form\EntryForm;

class IndexController extends AbstractActionController
{
    protected $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function indexAction()
    {
        $pageNum = $this->params()->fromQuery('p');
        $bets = $this->service->getPaginatedList($pageNum, 10);

        $betCount = array (
            "winning" => $this->service->getBetCount(1),
            "losing" => $this->service->getBetCount(0)
        );

        return new ViewModel(
            array(
                "bets" => $bets,
                'betCount' => $betCount
            )
        );
    }

    public function addAction() {

        $form = $this->service->getEntryForm();
        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $result = $this->service->create($request->getPost());

            if ($result === true) {
                return $this->redirect()->toRoute('bet');
            }
        }

        return new ViewModel(array('form' => $form));

    }

    public function editAction() {

        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $form = $this->service->getEntryForm();
            $result = $this->service->update($request->getPost());

            if ($result) {
                return $this->redirect()->toRoute('bet', array(
                    'controller' => 'index',
                    'action' =>  'edit',
                    'id' => $this->params('id')
                ));
            }
        }
        else {
            $form = $this->service->getEntryForm($this->params('id'));
        }

        return new ViewModel(array('form' => $form));

    }

    public function deleteAction() {

        $request = $this->getRequest();

        if ( !$this->params('id') || !$request->getPost('confirm') ) {
            $this->flashMessenger()->addMessage('To delete an entry, please browse to the management page, press delete & click "ok" to confirm you wish to delete it');
            return $this->redirect()->toRoute('bet');
        }

        $result = $this->service->delete($this->params('id'));
        $message = ($result == true ? 'Entry deleted successfully' : 'Unable to delete entry, please try again.');
        $this->flashMessenger()->addMessage($message);

        return $this->redirect()->toRoute('bet');

    }
}
