<?php namespace Matches\Repository;

use Application\AppClasses\Repository as AppRepository;

class EventRepository extends AppRepository\TaRepository {

    protected $entitySimpleName = 'Event';

    /**
     * @param $source
     * @return mixed
     */
    public function findEventBySource($source) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('Event')
            ->from('Matches\Entity\Event', 'Event')
            ->join('Matches\Entity\EventSource', 'es')
            ->where('es.name = :source')
            ->setParameters(array('source' => $source));

        $event = $qb->getQuery()->getSingleScalarResult();

        return $event;
    }

}
