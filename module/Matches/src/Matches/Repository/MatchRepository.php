<?php namespace Matches\Repository;

use Application\AppClasses\Repository as AppRepository;

class MatchRepository extends AppRepository\TaRepository
{
    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     */
    public function allBetween(\DateTime $from, \DateTime $to)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('m')
            ->from('Matches\Entity\Match', 'm')
            ->where('m.date >= :from')
            ->andWhere('m.date <= :to')
            ->orderBy('m.date')
            ->setParameters(
                array(
                    'from' => $from,
                    'to' => $to
                )
            );

        $matches = $qb->getQuery()->getResult();

        return $matches;
    }

}
