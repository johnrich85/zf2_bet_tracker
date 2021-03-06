<?php namespace Scraper\Service;

use Application\AppClasses\Service as TaService;
use Doctrine\ORM\EntityRepository;
use Matches\Entity\Match;
use Scraper\Entity\Source;
use Scraper\Entity\SourcePage;
use Scraper\Entity\SourcePageMatch;
use Scraper\Repository\SourcePageRepository;

class ScraperService extends TaService\TaService
{

    /**
     * @var EntityRepository
     */
    protected $sourceRepo;

    /**
     * @var SourcePageRepository
     */
    protected $pageRepo;

    /**
     * Constructor
     */
    public function __construct(EntityRepository $repo, SourcePageRepository $pageRepo)
    {
        $this->sourceRepo = $repo;
        $this->pageRepo = $pageRepo;
    }

    /**
     * @return array
     */
    public function allSources()
    {
        return $this->sourceRepo->findAll();
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     */
    public function allMatchPagesBetween(\DateTime $from, \DateTime $to)
    {
        return $this->pageRepo->allMatchesBetween($from, $to);
    }

    /**
     * @return array
     */
    public function getSourceNames()
    {
        $payload = [];

        $sources = $this->allSources();

        foreach ($sources as $source) {
            $payload[$source->getId()] = $source->getName();
        }

        return $payload;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function findSource($id)
    {
        return $this->sourceRepo->find($id);
    }

    /**
     * @return array
     */
    public function getPagesForSource($source)
    {
        if (!$source instanceof Source) {
            $source = $this->sourceRepo
                ->find($source);
        }

        $pages = $this->pageRepo
            ->allForSource($source);

        return $pages;
    }

    /**
     * @param array[SourcePage] $pages
     * @return array
     */
    public function getPageTitles(array $pages)
    {
        $payload = [];

        foreach ($pages as $page) {
            $payload[$page->getId()] = $page->getTitle();
        }

        return $payload;
    }

    /**
     * @param $entities
     */
    public function persistEntities($entities)
    {
        foreach ($entities as $entity) {
            try {
                $this->em->persist($entity);
            } catch (Exception $e) {
                continue;
            }
        }

        $this->em->flush();
    }

    /**
     * @param Source $source
     * @param array $matches
     */
    public function addPageForMatches(Source $source, array $matches)
    {
        $sourcePages = [];

        foreach ($matches as $match) {
            $sourcePage = new SourcePage();
            $sourcePage->setUri($match->getMatchSource());
            $sourcePage->setCaster('GosuMatchCaster');
            $sourcePage->setParser('\Scraper\Parsers\GosuMatchParser');
            $sourcePage->setTitle($match->toString());
            $sourcePage->setSource($source);

            $spm = $this->createSourcePageMatch($match, $sourcePage);

            $sourcePage->setMatch($spm);

            $sourcePages[] = $sourcePage;
        }


        $this->persistEntities($sourcePages);
    }

    /**
     * @param Match $match
     * @param SourcePage $page
     * @return SourcePageMatch
     */
    public function createSourcePageMatch(Match $match, SourcePage $page)
    {
        $spm = new SourcePageMatch();
        $spm->setMatch($match);
        $spm->setPage($page);

        $this->em->persist($spm);

        return $spm;
    }

    public function persist(\Doctrine\Entity $entity)
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        }
    }
} 