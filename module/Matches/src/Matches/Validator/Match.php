<?php namespace Matches\Validator;

use Illuminate\Contracts\Support\MessageBag;
use Matches\Entity\Sport;
use Matches\Entity\Team;
use Matches\Validator\Contract\Validator;
use Zend\InputFilter\InputFilter;

class Match extends Base implements Validator
{

    /**
     * @param InputFilter $inputFilter
     */
    public function __construct(InputFilter $inputFilter, MessageBag $bag)
    {
        parent::__construct($inputFilter, $bag);
    }

    protected function init()
    {
        $this->inputFilter->add(array(
            'name'     => 'first_team',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min'      => 0,
                        'max'      => 255,
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'second_team',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min'      => 0,
                        'max'      => 255,
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'date',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Date',
                    'options' => array(
                        'format'      => 'Y-m-d H:i:s'
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'created_at',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Date',
                    'options' => array(
                        'format'      => 'Y-m-d H:i:s'
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'updated_at',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Date',
                    'options' => array(
                        'format'      => 'Y-m-d H:i:s'
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'event',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min'      => 0,
                        'max'      => 255,
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'sport',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min'      => 0,
                        'max'      => 255,
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name'     => 'winner',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Between',
                    'options' => array(
                        'min'      => 0,
                        'max'      => 2,
                    ),
                ),
            ),
        ));
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {

        if ($data['first_team'] instanceof Team) {
            $data['first_team'] = $data['first_team']->getName();
        }

        if ($data['second_team'] instanceof Team) {
            $data['second_team'] = $data['second_team']->getName();
        }

        if ($data['date'] instanceof \DateTime) {
            $data['date'] = $data['date']->format('Y-m-d H:i:s');
        }

        if ($data['event'] instanceof \Matches\Entity\Event) {
            $data['event'] = $data['event']->getName();
        }

        if ($data['sport'] instanceof Sport) {
            $data['sport'] = $data['sport']->getName();
        }

        parent::setData($data);
    }
}