<?php namespace Bet\Validator;

use Particle\Validator\Validator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BetValidatorFactory
 *
 * @package Bet\Validator
 */
class BetValidatorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $validator = new Validator();

        $betValidator = new BetValidator($validator);

        return $betValidator;
    }
}