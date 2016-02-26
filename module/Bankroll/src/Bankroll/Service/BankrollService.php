<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 06/12/14
 * Time: 13:43
 */

namespace Bankroll\Service;

use Application\AppClasses\Service as TaService;
use Bankroll\Entity\Bankroll;

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

    /**
     * @param $bankroll
     * @param array $data
     * @return bool|int
     * @throws Exception
     * @todo validation
     */
    public function update($bankroll, $data = array())
    {
        $bankroll = $this->getBankrollInstance($bankroll);

        if(!$bankroll) {
            return false;
        }

        $bankroll->populate($data);

        $this->persist($bankroll);

        return $bankroll;
    }

    /**
     * @param $bankroll Bankroll|int
     * @param $value int
     * @return bool|Bankroll
     */
    public function amendAmount($bankroll, $value) {
        $bankroll = $this->getBankrollInstance($bankroll);

        if(!$bankroll) {
            return false;
        }

        $bankroll->amendAmount($value);

        return $bankroll;
    }

    /**
     * @param \Bankroll\Entity\Bankroll $bankroll
     * @throws Exception
     */
    public function persist(\Bankroll\Entity\Bankroll $bankroll) {
        try {
            $this->em->persist($bankroll);
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        }
    }

    /**
     * @param $bankroll Bankroll|int
     * @return Bankroll
     */
    protected function getBankrollInstance($bankroll) {
        if(!$bankroll instanceof Bankroll ) {
            $id = (int) $bankroll;

            $bankroll = $this->bankrollRepository
                ->findOneById($id);
        }

        return $bankroll;
    }

    public function getById($id)
    {
        return $this->bankrollRepository->find($id);
    }
} 