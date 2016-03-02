<?php namespace Scraper\Form;

use Zend\Form\Form;

class Source extends Form
{

    /**
     * @var
     */
    protected $sources;

    /**
     * Source constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct('entry');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/scraper/pages');
    }

    /**
     * @param array $sources
     */
    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Init form
     */
    public function init()
    {
        parent::init();

        $this->add(array(
            'type' => 'select',
            'name' => 'source',
            'options' => array(
                'label' => 'Sources',
                'empty_option' => 'Select a source',
                'value_options' => $this->sources,
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'View pages',
                'id' => 'submitbutton',
            ),
        ));
    }
} 