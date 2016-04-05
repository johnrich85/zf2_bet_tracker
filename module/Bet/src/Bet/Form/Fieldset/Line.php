<?php namespace Bet\Form\Fieldset;

use Zend\Form\Fieldset;

class Line extends Fieldset
{
    /**
     * @var int
     */
    protected $line_num = 1;

    public function __construct()
    {
        parent::__construct('line');

        $this->setAttribute('class', 'bet-line');

        $this->setLabel('Bet Line ' . $this->line_num);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'line_1[match]',
            'attributes' => array(
                'type' => 'hidden',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Match name',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'line_1[selection]',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'e.g TSM to lose',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'line_1[odds]',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'e.g 5/2',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'line_1[win]',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'label' => 'Line status',
                'options' => [
                    '*Pending*',
                    'Lose',
                    'Win'
                ],
                'class' => 'form-control'
            )
        ));
    }
}