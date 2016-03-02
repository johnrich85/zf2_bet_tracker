<?php namespace Matches\Repository;

use Application\AppClasses\Repository as AppRepository;
use Doctrine\ORM\Query\Expr\Join;
use Matches\Entity\Event;

class EventRepository extends AppRepository\TaRepository
{

    protected $entitySimpleName = 'Event';

    /**
     * @param $source
     * @return mixed
     */
    public function findEventBySource($source)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('e')
            ->from('Matches\Entity\Event', 'e')
            ->join('Matches\Entity\EventSource', 'es', Join::WITH, $qb->expr()->eq('e.id', 'es.event'))
            ->where('es.url = :source')
            ->setParameters(array('source' => $source));

        $event = $qb->getQuery()->getOneOrNullResult();

        return $event;
    }

    /**
     * @return Event|mixed
     */
    public function findDefault()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('e')
            ->from('Matches\Entity\Event', 'e')
            ->where("e.name = 'Default'");

        $event = $qb->getQuery()->getOneOrNullResult();

        return $event;
    }
}
