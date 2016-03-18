<?php

namespace Application\AppClasses\Repository;

use Doctrine\ORM\EntityRepository;

class TaRepository extends EntityRepository
{

    protected $entitySimpleName;

    /**
     * Given an array of parameters, adds where clause/s
     * to query builder.
     *
     * @param $query
     * @param string[] $params
     */
    public function setWhereClauseParams($query, array $params)
    {
        $count = 0;
        foreach ($params as $key => $param) {
            $queryString = $this->entitySimpleName . '.' . $key . '=:' . $key;

            if ($count == 0) {
                $query->where($queryString);
                continue;
            }

            $query->andWhere($queryString);

        }

        $query->setParameters($params);
    }

}