<?php namespace Scraper\Service;

use Application\AppClasses\Service as TaService;

class ScraperService extends TaService\TaService {

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * @param $entities
     * @todo validation
     */
    public function persistEntities($entities) {
        foreach($entities as $entity) {
            try {
                $this->em->persist($entity);
            } catch (Exception $e) {
                continue;
            }
        }

        $this->em->flush();
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