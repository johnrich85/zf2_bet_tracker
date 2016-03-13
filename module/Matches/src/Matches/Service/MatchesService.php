<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;
use Matches\Entity\Match;
use Matches\Validator\Contract\Validator;

class MatchesService extends TaService\TaService
{

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
    public function __construct($matchesRepository, Validator $matchValidator)
    {
        $this->matchesRepository = $matchesRepository;
        $this->matchValidator = $matchValidator;
    }

    /**
     * @param array $params
     * @return null|object
     */
    public function findOneBy(array $params)
    {
        return $this->matchesRepository
            ->findOneBy($params);
    }

    /**
     * @param $hash
     * @return mixed
     */
    public function getByHash($hash)
    {
        return $this->matchesRepository
            ->findOneBy(array('hash' => $hash));
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return mixed
     */
    public function allBetween(\DateTime $from, \DateTime $to)
    {
        return $this->matchesRepository->allBetween($from, $to);
    }

    /**
     * @param array $data
     * @return bool|\Matches\Entity\Match
     */
    public function newInstance(array $data)
    {
        $this->matchValidator->setData($data);

        if ($this->matchValidator->isValid()) {
            $match = new \Matches\Entity\Match();
            $match->populate($data);
            $match->setHash($match->toHash());

            return $match;
        } else {
            return false;
        }
    }

    /**
     * Creates an instance if data is valid.
     *
     * @param array $data
     * @return bool|Match
     */
    public function create(array $data)
    {
        $this->matchValidator->setData($data);

        if ($this->matchValidator->isValid()) {
            $match = new Match();
            $match->populate($data);

            $this->em->persist($match);

            return $match;
        } else {
            return false;
        }
    }

    /**
     * Persists a given match
     *
     * @throws Exception
     */
    public function persist(\Matches\Entity\Match $match)
    {
        try {
            $this->em->persist($match);
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        }
    }
} 