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

        $variables = [];

        if (!$id) {
            $this->notFoundAction();
        }

        $page = $this->sourcePageRepo
            ->eagerFind($id);

        try {
            $sm = $this->getServiceLocator();
            $scraper = new GuzzleScraper($sm);

            $data = $scraper->fetch($page);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $variables['message'] = $error;

            return $this->fetchView(
                $variables,
                'scraper/connection-error'
            );
        }

        if ($data) {
            $this->scraperService->persistEntities($data);

            $variables['message'] = 'New data added.';
        } else {
            $variables['message'] = 'Nothing new to add.';
        }

        return $this->fetchView($variables);
    }
}
