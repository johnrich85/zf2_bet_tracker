<?php namespace Scraper\Controller;

use Application\AppClasses\Controller\TaController;
use Scraper\Scraper\Facade\GuzzleScraper;
use Scraper\Service\ScraperService;

class CommandController extends TaController
{
    /**
     * @var MatchesService
     */
    protected $scraperService;

    /**
     * @param ScraperService $scraperService
     */
    public function __construct(ScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    /**
     * Scrapes matches, returning additional info such as
     * winner, game details, date etc.
     */
    public function scrapeMatchesAction()
    {
        $from = $this->getStartDate();
        $to = $this->getEndDate();

        $pages = $this->scraperService
            ->allMatchPagesBetween($from, $to);

        $sm = $this->getServiceLocator();

        foreach ($pages as $page) {
            sleep(rand(0, 3));

            try {
                $scraper = new GuzzleScraper($sm);
                $data = $scraper->fetch($page);
            } catch (\Exception $e) {
                $message = 'Unable to scrape data for page:' . $page->getTitle();
                error_log($message);

                continue;
            }

            $this->scraperService->persistEntities($data);
        }

        echo("Scraping completed");
    }

    /**
     * @return \DateTime
     */
    protected function getStartDate()
    {
        $from = new \DateTime();
        $from->modify('-2 months');
        $from->setTime(0, 0, 0);

        return $from;
    }

    /**
     * @return \DateTime
     */
    protected function getEndDate()
    {
        $to = new \DateTime();
        $to->modify('-3 hours');

        return $to;
    }
}
