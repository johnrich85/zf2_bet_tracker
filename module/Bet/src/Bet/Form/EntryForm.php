<?php

namespace Bet\Form;

use Zend\Form\Form;

class EntryForm extends Form
{
    protected $matches;

    public function __construct($name = null)
    {
        parent::__construct('entry');

        $this->setAttribute('method', 'post');

        $this->add(array(
                'name' => 'id',
                'attributes' => array(
                    'type'  => 'hidden',
                    'placeholder' => 'ID'
                )
        ));

        $this->add(array(
            'name' => 'userId',
            'attributes' => array(
                'type'  => 'hidden',
                'placeholder' => 'ID',
                'value' => '1',
            ),
            'value' => '1',
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Name / Title',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'date',
            'type' => 'date',
            'options' => array(
                'format' => 'Y-m-d',
            ),
            'attributes' => array(
                'required'  => 'required',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'amount',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Bet amount',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'return',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Total return',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'successful',
            'type'  => 'checkbox',
            'options' => array(
                'label' => 'Enter as a winning bet?',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )

        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add',
                'id' => 'submitbutton',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'secret',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));

        $this->setAttribute('action', '/bet/add');
    }

    /**
     * Switches the form to edit mode.
     */
    public function editMode()
    {
        $ele = $this->get('id');
        $id = $ele->getValue();

        $this->setAttribute('action', '/bet/edit/'.$id);
        $this->get('submit')->setValue('Edit');
    }
} 