<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;
use Doctrine\ORM\EntityRepository;
use Matches\Validator\Contract\Validator;

class Game extends TaService\TaService
{
    /**
     * @var
     */
    protected $gameRepository;

    /**
     * Constructor
     */
    public function __construct(EntityRepository $gameRepo, Validator $validator)
    {
        $this->gameRepository = $gameRepo;
        $this->gameValidator = $validator;
    }

    /**
     * Creates & persists Game Entity.
     * Returns false if data invalid.
     *
     * @param array $data
     * @return bool|\Matches\Entity\Team
     */
    public function create(array $data)
    {
        $this->gameValidator->setData($data);

        if ($this->gameValidator->isValid()) {
            $game = new \Matches\Entity\Game();
            $game->populate($data);

            $this->em->persist($game);

            return $game;
        } else {
            return false;
        }
    }
}