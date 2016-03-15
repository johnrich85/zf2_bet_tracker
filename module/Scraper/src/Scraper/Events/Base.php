<?php namespace Scraper\Events;

use Scraper\Events\Contract\PageScraperHandler;
use Zend\EventManager\EventInterface;

class Base implements PageScraperHandler
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var MatchesService
     */
    protected $service;

    /**
     * @var SourcePage
     */
    protected $page;

    /**
     * @param EventInterface $e
     */
    public function handle(EventInterface $e)
    {
        $this->params = $e->getParams();

        if (!$this->hasValidParams()) {
            return;
        }

        $this->service = $this->params['service'];
        $this->page = $this->params['page'];

        if ($this->page->getCaster() == 'GosuLoLCaster') {
            $this->handleGosuMatchScrape();
        }
    }

    /**
     * Handles completion of gosu match scape.
     */
    protected function handleGosuMatchScrape()
    {
        $source = $this->service->findSource(2);

        $matches = $this->params['matches'];

        $this->service
            ->addPageForMatches($source, $matches);
    }

    /**
     * @return bool
     */
    protected function hasValidParams()
    {
        if (empty($this->params['service']) ||
            empty($this->params['matches']) ||
            empty($this->params['page'])) {
            return false;
        }

        return true;
    }
}