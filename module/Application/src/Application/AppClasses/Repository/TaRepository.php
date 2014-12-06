<?php

namespace Application\AppClasses\Repository;

use Doctrine\ORM\EntityRepository;

class TaRepository extends EntityRepository{

    protected $entitySimpleName;

    /**
     * Given an array of parameters, returns a QueryBuilder
     * instance.
     *
     * Provides the same ability as 'findBy', but uses the querybuilder
     * so as to return a result compatible with 'ORMPaginator'.
     *
     * @param string[] $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function QueryBuilderFindBy(array $params)
    {
        if (empty($this->entitySimpleName))
        {
            throw new \Exception("Property 'entitySimpleName' must not have a value of null");
        }

        $query = $this->createQueryBuilder($this->entitySimpleName);
        $this->setWhereClauseParams($query,$params);

        $query->orderBy($this->entitySimpleName . '.date','DESC');

        return $query;
    }

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
        foreach($params as $key=>$param)
        {
            $queryString = $this->entitySimpleName . '.' . $key . '=:' . $key;

            if ($count == 0)
            {
                $query->where($queryString);
                continue;
            }

            $query->andWhere($queryString);

        }

        $query->setParameters($params);
    }

} 