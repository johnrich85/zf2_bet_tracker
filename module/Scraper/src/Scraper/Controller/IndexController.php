<?php namespace Scraper\Controller;

use Application\AppClasses\Controller\TaController;
use Matches\Service\MatchesService;
use Scraper\Form\Source;
use Scraper\Form\SourcePages;
use Scraper\Repository\SourcePageRepository;
use Scraper\Service\ScraperService;
use Scraper\Scraper\Facade\GuzzleScraper;

class IndexController extends TaController
{
    /**
     * @var SourcePageRepository
     */
    protected $sourcePageRepo;

    /**
     * @var MatchesService
     */
    protected $scraperService;

    /**
     * @param SourcePageRepository $repo
     * @param ScraperService $scraperService
     */
    public function __construct(SourcePageRepository $repo, ScraperService $scraperService)
    {
        $this->sourcePageRepo = $repo;
        $this->scraperService = $scraperService;
    }

    /**
     * Shows list of Sources
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $form = new Source();

        $sources = $this->scraperService
            ->getSourceNames();

        $form->setSources($sources);
        $form->init();

        return $this->fetchView([
            'form' => $form
        ]);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function pagesAction()
    {
        $id = $this->params()
            ->fromPost('source');

        if (!$id) {
            $this->notFoundAction();
        }

        $form = new SourcePages();

        $pages = $this->scraperService->getPageTitles(
            $this->scraperService->getPagesForSource($id)
        );

        $form->setPages($pages);
        $form->init();

        return $this->fetchView([
            'form' => $form
        ]);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function scrapeAction()
    {
        $id = $this->params()->fromPost('page');

        $sm = $this->getServiceLocator();

        if (!$id) {
            $this->notFoundAction();
        }

        $page = $this->sourcePageRepo->eagerFind($id);

        try {
            $scraper = new GuzzleScraper($sm);
            $data = $scraper->fetch($page);
        } catch (\Exception $e) {
            $error = $e->getMessage();

            return $this->fetchView(
                [
                    'message' => $error
                ],
                'scraper/connection-error'
            );
        }

        $message = 'Nothing new to add.';
        if ($data) {
            $this->scraperService->persistEntities($data);

            //TODO remove - this is not generic and so can't live here.
            $source = $this->scraperService
                ->findSource(2);

            $this->scraperService->addPageForMatches($source, $data);
            //end todo.

            $message = 'New data added.';
        }

        return $this->fetchView([
                "message" =>$message
        ]);
    }
}
