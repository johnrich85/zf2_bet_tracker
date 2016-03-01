<?php namespace Matches\Validator;

use Illuminate\Contracts\Support\MessageBag;
use Zend\InputFilter\InputFilter;

class Base {

    /**
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * @var MessageBag
     */
    protected $messageBag;

    /**
     * @param InputFilter $inputFilter
     * @param MessageBag $bag
     * @throws \Exception
     */
    public function __construct(InputFilter $inputFilter, MessageBag $bag) {
        $this->inputFilter = $inputFilter;
        $this->messageBag = $bag;

        $this->init();
    }

    /**
     * @return MessageBag
     */
    public function getMessageBag() {
        return $this->messageBag;
    }

    /**
     * @return bool
     */
    public function isValid() {
        if($this->inputFilter->isValid()) {
            return true;
        }
        else {
            var_dump($this->inputFilter->getMessages()); die();
            return false;
        }
    }

    /**
     * @param array $data
     */
    public function setData(array $data) {
        $this->inputFilter->setData($data);
    }

    /**
     * Stub method - to be overriden;
     */
    protected function init() {
        throw new \Exception('Must implement init() method on sub-classes.');
    }
}