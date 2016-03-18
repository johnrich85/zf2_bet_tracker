<?php namespace Bet\Repository;

use Application\AppClasses\Repository as AppRepository;
use Doctrine\DBAL\Query\QueryBuilder;

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
    public function getBetCountByStatus($successful)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(bet)')
            ->from($this->getEntityName(), 'bet')
            ->where('bet.successful = :successful')
            ->setParameters(array('successful' => $successful));

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }

    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function all(array $params)
    {
        $query = $this->createQueryBuilder($this->entitySimpleName);

        $this->setWhereClauseParams($query, $params);

        $query->orderBy($this->entitySimpleName . '.date', 'DESC');

        return $query;
    }

    /**
     * @param $page
     * @param $take
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function allByDate($page, $take, array $params)
    {
        $query = $this->all($params);

        $query->setFirstResult($page);

        $query->setMaxResults($take);

        $query->select('date(bet.date) as theDate, SUM(bet.return) - SUM(bet.amount) as total');

        $this->setWhereClauseParams($query, $params);

        $query->groupBy('theDate');

        return $query;
    }

    /**
     * @param $query \Doctrine\ORM\QueryBuilder
     * @return int
     */
    public function totalRows(\Doctrine\ORM\QueryBuilder $query)
    {
        $query->setMaxResults(null);
        $query->setFirstResult(null);

        return count($query->getQuery()->getArrayResult());
    }
}
