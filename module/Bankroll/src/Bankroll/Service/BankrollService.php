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

    /**
     * @var \Bankroll\Repository\BankrollRepository
     */
    protected $bankrollRepository;

    /**
     * Constructor
     */
    public function __construct($bankrollRepository) {
        $this->bankrollRepository = $bankrollRepository;
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function getById($id)
    {
        return $this->bankrollRepository->find($id);
    }
} 