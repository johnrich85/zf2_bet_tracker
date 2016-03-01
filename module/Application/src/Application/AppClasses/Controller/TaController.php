<?php
namespace Application\AppClasses\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TaController extends AbstractActionController{

    /**
     * @var \Illuminate\Support\MessageBag
     */
    protected $messageBag;

    /**
     * Adds a message to messages array.
     * (messages are rendered at top of the page)
     *
     * @param $message
     */
    public function addMessage($message) {
        array_push($this->messages, $message);
    }

    /**
     * Passes data to view model and returns.
     *
     * @param array $data
     * @return ViewModel
     */
    public function fetchView($data = array(), $template = null) {
        if(!$this->messageBag) {
            $this->messageBag = $this->getServiceLocator()->get('MessageBag');
        }

        $this->layout()->messages = $this->messageBag;

        $viewModel = new ViewModel($data);

        if($template) {
            $viewModel->setTemplate($template);
        }

        return $viewModel;
    }
}