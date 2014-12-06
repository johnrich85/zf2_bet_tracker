<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 06/07/14
 * Time: 10:45
 */

namespace Bet\Repository;

use Application\AppClasses\Repository as AppRepository;

class BetRepository extends AppRepository\TaRepository{

    protected $entitySimpleName = 'bet';

    /**
     * Overrides doctrine 'findAll' method, providing the
     * ability to sort.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(array(), array('date' => 'DESC'));
    }


} 