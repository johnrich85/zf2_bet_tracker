<?php namespace Matches\Validator\Contract;

interface Validator {
    /**
     * @param array $data
     * @return null
     *
     */
    public function setData(array $data);

    /**
     * @return boolean
     */
    public function isValid();

    /**
     * @return MessageBag
     */
    public function getMessageBag();
}