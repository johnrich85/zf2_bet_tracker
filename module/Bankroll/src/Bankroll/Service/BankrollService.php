<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 06/12/14
 * Time: 13:43
 */

namespace Bankroll\Service;

use Application\AppClasses\Service as TaService;

class BankrollService extends TaService\TaService {

    public function getRepository() {
        return $this->em->getRepository('Bankroll\Entity\Bankroll');
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function getById($id)
    {
        return $this->getRepository()->find($id);
    }
} 