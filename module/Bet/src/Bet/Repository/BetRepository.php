<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 06/07/14
 * Time: 10:45
 */

namespace Bet\Repository;

use Doctrine\ORM\EntityRepository;

class BetRepository extends EntityRepository{

    /**
     * Overrides doctrine 'findAll' method, providing the
     * ability to sort.
     *
     * @return array
     */
    public function findAll() {
        return $this->findBy(array(), array('date' => 'DESC'));
    }

    public function getBetCount() {

    }
} 