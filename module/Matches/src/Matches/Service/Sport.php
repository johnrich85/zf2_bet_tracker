<?php namespace Matches\Service;

use Application\AppClasses\Service as TaService;
use Doctrine\ORM\EntityRepository;

class Sport extends TaService\TaService
{
    /**
     * @var
     */
    protected $sportRepository;

    /**
     * @param EntityRepository $sportRepo
     */
    public function __construct(EntityRepository $sportRepo)
    {
        $this->sportRepository = $sportRepo;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->sportRepository
            ->find(1);
    }

}