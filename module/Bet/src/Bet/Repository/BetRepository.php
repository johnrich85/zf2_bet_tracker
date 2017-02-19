<?php namespace Bet\Repository;

use Application\AppClasses\Repository as AppRepository;
use Bet\Entity\Bet;
use Doctrine\DBAL\LockMode;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class BetRepository extends AppRepository\TaRepository {

    protected $entitySimpleName = 'bet';

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function find($id)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('b, bl')
            ->from('Bet\Entity\Bet', 'b')
            ->join('b.lines', 'bl')
            ->join('bl.match', 'm')
            ->where('b.id = :id')
            ->setParameters(
                array('id' => $id)
            );

        $bet = $qb->getQuery()
            ->getOneOrNullResult();

        return $bet;
    }

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
    public function allByDate($skip, $take, array $params)
    {
        $query = $this->all($params);

        $query->setFirstResult($skip);

        $query->setMaxResults($take);

        $query->select('date(bet.date) as theDate, SUM(bet.calculated_profit) as total');

        $this->setWhereClauseParams($query, $params);

        $query->groupBy('theDate');

        return $query;
    }

    public function allByMonth($skip, $take, array $params)
    {
        $query = $this->allByDate($skip, $take, $params);

        $dql  = 'concat(month(bet.date), year(bet.date)) as HIDDEN theDate, ';
        $dql .= 'SUM(bet.calculated_profit) as total, ';
        $dql .= 'date(bet.date) as date';

        $query->select($dql);

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
