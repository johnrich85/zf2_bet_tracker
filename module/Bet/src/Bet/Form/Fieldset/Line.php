<?php namespace Bet\Form\Fieldset;

use Zend\Form\Fieldset;

class Line extends Fieldset
{
    /**
     * @var int
     */
    protected $line_num = 1;

    /**
     * @var string
     */
    protected $name_format = "line_<num>[<name>]";

    /**
     * Line constructor.
     *
     * @param int|null|string $name
     * @param array $options
     */
    public function __construct($name, $options = [])
    {
        parent::__construct($name, $options);

        $this->line_num = $options['index'];

        $this->setAttribute('class', 'bet-line');

        $this->setLabel('Bet Line ' . $this->getDisplayLabel());

        $this->add(array(
            'name' => $this->generateLineName($this->line_num, 'id'),
            'attributes' => array(
                'type' => 'hidden',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => $this->generateLineName($this->line_num, 'match'),
            'attributes' => array(
                'type' => 'hidden',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => $this->generateLineName($this->line_num, 'name'),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Match name',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => $this->generateLineName($this->line_num, 'selection'),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'e.g TSM to lose',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => $this->generateLineName($this->line_num, 'odds'),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'e.g 5/2',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => $this->generateLineName($this->line_num, 'win'),
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

    /**
     * Prefixes and populates values.
     *
     * @param array|\Traversable $data
     */
    public function populateValues($data)
    {
        $data = $this->prefixKeys($data);

        parent::populateValues($data);
    }

    /**
     * Prefixes the data passed in so that it
     * matches the fieldnames.
     *
     * @param $data
     * @return array
     */
    protected function prefixKeys($data)
    {
        $payload = [];

        $lineNum = $this->line_num;

        foreach($data as $key=>$value) {
            $newKey = $this->generateLineName($lineNum, $key);

            $payload[$newKey] = $value;
        }

        return $payload;
    }

    /**
     * Generates a line name based on current
     * line number and the name of the field.
     *
     * @param $index
     * @param $fieldName
     * @return mixed|string
     */
    protected function generateLineName($index, $fieldName)
    {
        $name = $this->name_format;

        $name = str_replace('<num>', $index, $name);

        $name = str_replace('<name>', $fieldName, $name);

        return $name;
    }

    /**
     * @return int|mixed
     */
    private function getDisplayLAbel()
    {
       return $this->line_num + 1;
    }
}