<?php namespace Bet\Repository;

use Application\AppClasses\Repository as AppRepository;

class BetRepository extends AppRepository\TaRepository {

    protected $entitySimpleName = 'bet';

    /**
     * Overrides doctrine 'findAll' method, changing
     * the sort order.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(array(), array('date' => 'DESC'));
    }

    /**
     * Given a value of 1, returns the number of
     * successful bets. Given 0, returns unsuccessful
     * bets.
     *
     * @param $successful
     * @return mixed
     */
    public function getBetCountByStatus($successful) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(bet)')
            ->from('Bet\Entity\Bet', 'bet')
            ->where('bet.successful = :successful')
            ->setParameters(array('successful' => $successful));

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }

}
