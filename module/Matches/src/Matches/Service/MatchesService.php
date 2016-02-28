<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;

class MatchesService extends TaService\TaService {

    /**
     * @var \Bankroll\Repository\MatchesRepository
     */
    protected $matchesRepository;

    /**
     * Constructor
     */
    public function __construct($matchesRepository) {
        $this->matchesRepository = $matchesRepository;
    }
} 