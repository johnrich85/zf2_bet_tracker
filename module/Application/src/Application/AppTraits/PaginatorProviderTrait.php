<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 08/07/14
 * Time: 20:13
 */

namespace Application\AppTraits;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

trait PaginatorProviderTrait
{

    private $perPage = 10;

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getPaginator($queryBuilder)
    {
        $adapter = new DoctrineAdapter(
            new ORMPaginator(
                $queryBuilder
            )
        );

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($this->perPage);

        return $paginator;
    }




} 