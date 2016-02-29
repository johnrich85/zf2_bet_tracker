<?php namespace Scraper\Service;

use Application\AppClasses\Service as TaService;

class ScraperService extends TaService\TaService {

    /**
     * Constructor
     */
    public function __construct() {

    }

    public function persist(\Doctrine\Entity $entity) {
        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        }
    }
} 