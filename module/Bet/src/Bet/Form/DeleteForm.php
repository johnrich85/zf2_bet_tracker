<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 01/05/14
 * Time: 20:47
 */

namespace Bet\Form;

use Zend\Form\Form;

class DeleteForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('delete');
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
                'placeholder' => 'userId'
            )
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'confirmation',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Enter The bet name here.',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Delete',
                'id' => 'submitbutton btn btn-danger',
                'class' => 'btn btn-danger',
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
    }
} 