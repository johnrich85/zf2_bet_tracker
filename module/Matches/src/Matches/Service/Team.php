<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;
use Doctrine\ORM\EntityRepository;
use Matches\Validator\Contract\Validator;

class Team extends TaService\TaService
{
    /**
     * @var
     */
    protected $teamRepository;

    /**
     * Constructor
     */
    public function __construct(EntityRepository $teamRepo, Validator $validator)
    {
        $this->teamRepository = $teamRepo;
        $this->teamValidator = $validator;
    }

    /**
     * @param array $params
     * @return null|object
     */
    public function findOneBy(array $params)
    {
        return $this->teamRepository->findOneBy($params);
    }

    /**
     * Creates & persists Team Entity.
     * Returns false if data invalid.
     *
     * @param array $data
     * @return bool|\Matches\Entity\Team
     */
    public function create(array $data)
    {
        $this->teamValidator->setData($data);

        if ($this->teamValidator->isValid()) {
            $team = new \Matches\Entity\Team();
            $team->populate($data);

            $this->em->persist($team);

            return $team;
        } else {
            return false;
        }
    }
}