<?php namespace Bet\Validator;

use Application\AppInterface\ValidatorInterface;
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;

/**
 * Class BetValidator
 *
 * @package Bet\Validator
 */
class BetValidator implements ValidatorInterface
{
    /**
     * @var
     */
    protected $validator;

    /**
     * @var ValidationResult
     */
    protected $result;

    /**
     * BetValidator constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;

        $this->addValidators();
    }

    /**
     * Adds validators
     */
    protected function addValidators()
    {
        $this->validator->required('name')
            ->lengthBetween(1, 100)
            ->alpha();


        $this->validator->required('date')
            ->datetime('Y-m-d H:i:s');

        $this->validator->required('amount')
            ->lengthBetween(1, 100000)
            ->integer();

        $this->validator->required('return')
            ->lengthBetween(0, 100000)
            ->integer();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data)
    {
        $this->result = $this->validator->validate($data);

        return $this->result->isValid();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        if(!$this->result) {
            return [];
        }

        return $this->result->getMessages();
    }


}