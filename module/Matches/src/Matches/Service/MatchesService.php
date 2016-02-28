<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;

class MatchesService extends TaService\TaService {

    /**
     * @var
     */
    protected $matchesRepository;

    /**
     * Constructor
     */
    public function __construct($matchesRepository) {
        $this->matchesRepository = $matchesRepository;
    }

    /**
     * Persists a given match
     *
     * @throws Exception
     * @todo need logging & graceful handling of exceptions
     */
    public function persist(\Matches\Entity\Match $match) {
        try {
            $this->em->persist($match);
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        }
    }
} 