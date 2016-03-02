<?php namespace Scraper\Form;

use Zend\Form\Form;

class SourcePages extends Form
{

    /**
     * @var
     */
    protected $pages;

    /**
     * Source constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct('entry');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/scraper/scrape');
    }

    /**
     * @param array $sources
     */
    public function setPages(array $pages)
    {
        $this->pages = $pages;
    }

    /**
     * Init form
     */
    public function init()
    {
        parent::init();

        $this->add(array(
            'type' => 'select',
            'name' => 'page',
            'options' => array(
                'label' => 'Sources',
                'empty_option' => 'Select a page to scrape',
                'value_options' => $this->pages,
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Scrape it good, babe',
                'id' => 'submitbutton',
            ),
        ));
    }
} 