<?php namespace Application\AppInterface;

/**
 * Interface ValidatorInterface
 * @package Application\AppInterface
 */
interface ValidatorInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function isValid(array $data);

    /**
     * @return mixed
     */
    public function getMessages();
}