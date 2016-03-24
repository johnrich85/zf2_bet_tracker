<?php namespace Bet\Form\Fieldset;

use Bet\Entity\BetLine;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class Line extends Fieldset
{
    public function __construct()
    {
        parent::__construct('line');

        //TODO
//        $this->setLabel('Line');
//
//        $this->add(array(
//            'name' => 'id',
//            'attributes' => array(
//                'type' => 'hidden',
//                'class' => 'form-control'
//            )
//        ));
//
//        $this->add(array(
//            'name' => 'name',
//            'attributes' => array(
//                'type' => 'text',
//                'placeholder' => 'Match name',
//                'class' => 'form-control'
//            )
//        ));
//
//        $this->add(array(
//            'name' => 'line_1[selection]',
//            'attributes' => array(
//                'type' => 'text',
//                'placeholder' => 'e.g TSM to lose',
//                'class' => 'form-control'
//            )
//        ));
//
//        $this->add(array(
//            'name' => 'bet_line_1[odds]',
//            'attributes' => array(
//                'type' => 'text',
//                'placeholder' => 'e.g 5/2',
//                'class' => 'form-control'
//            )
//        ));
//
//        $this->add(array(
//            'name' => 'line_1[win]',
//            'attributes' => array(
//                'type' => 'Zend\Form\Element\Select',
//                'label' => 'Line status',
//                'options' => [
//                    '*Pending*',
//                    'Lose',
//                    'Win'
//                ],
//                'class' => 'form-control'
//            )
//        ));
//
//        $this->add(array(
//            'name' => 'line_1[match]',
//            'attributes' => array(
//                'type' => 'hidden',
//                'class' => 'form-control'
//            )
//        ));
    }
}