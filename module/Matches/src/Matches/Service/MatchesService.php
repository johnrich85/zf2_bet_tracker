<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;
use Matches\Validator\Contract\Validator;

class MatchesService extends TaService\TaService {

    /**
     * @var
     */
    protected $matchesRepository;

    /**
     * @var
     */
    protected $matchValidator;

    /**
     * Constructor
     */
    public function __construct($matchesRepository, Validator $matchValidator) {
        $this->matchesRepository = $matchesRepository;
        $this->matchValidator = $matchValidator;
    }

    /**
     * @param array $data
     * @return bool|\Matches\Entity\Match
     */
    public function newInstance(array $data) {
        $this->matchValidator->setData($data);

        if($this->matchValidator->isValid()) {
            $match = new \Matches\Entity\Match();
            $match->populate($data);

            return $match;
        }
        else {
            return false;
        }
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