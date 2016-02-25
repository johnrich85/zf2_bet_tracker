<?php

namespace Bet\Service;

use Application\AppClasses\Service as TaService;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;
use Bankroll\Entity\Bankroll;
use Bet\Entity\Bet;


class BetService extends TaService\TaService implements PaginatationProviderInterface {

    use PaginatorProviderTrait;

    /**
     * @var int
     */
    protected $userId = 1;

    /**
     * @return mixed
     */
    public function getRepository() {
        return $this->em->getRepository('Bet\Entity\Bet');
    }

    /**
     * @return mixed
     */
    public function getList() {
        return $this->getRepository()->findAll();
    }

    /**
     * @param $page
     * @param $params
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginatedList($page, $params) {
        $query = $this->getRepository()->QueryBuilderFindBy($params);

        $paginator = $this->getPaginator($query);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getEntryForm($id = null) {
        $this->form = $this->sm->get('BetForm');

        if (!$id) {
            return $this->form;
        }

        if ($bet = $this->em->find('Bet\Entity\Bet', $id) ) {
            $this->form->bind($bet);
        }

        return $this->form;
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function create($data) {
        if ( !$this->form ) $this->getEntryForm();

        $bet = $this->sm->get('BetEntity');

        $this->form->setInputFilter($bet->getInputFilter());
        $this->form->setData($data);

        if ( $this->form->isValid() ) {

            $bet->exchangeArray($this->form->getData());
            $betValue = $bet->calculateProfitOrLoss();

            $bankroll = $this->em->getRepository('Bankroll\Entity\Bankroll')
                ->findOneById($this->userId);

            $bankroll->amendAmount($betValue);

            $this->persistBetAndBankroll($bet, $bankroll);

            return true;
        }

        return false;
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function update($data) {
        if (!$this->form)
            $this->getEntryForm();

        $bet = $this->em->find('Bet\Entity\Bet', $data->id);

        if ($bet == false) {
            $exceptionMsg = "Error, trying to update non-existent Bet with id of: " . $data->id;
            throw new \Exception($exceptionMsg);
        }

        $this->form->setInputFilter($bet->getInputFilter());
        $this->form->setData($data);

        if ($this->form->isValid()) {
            $oldPL = $bet->calculateProfitOrLoss();

            $bet->exchangeArray($this->form->getData());

            $difference = $bet->calculateProfileLossDifference($oldPL);

            $bankroll = $this->em->getRepository('Bankroll\Entity\Bankroll')
                ->findOneById($this->userId);

            $bankroll->amendAmount($difference);

            $this->persistBetAndBankroll($bet, $bankroll);

            return true;
        }

        return false;
    }

    /**
     * @param $successful
     * @return mixed
     */
    public function getBetCount($successful) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(bet)')
            ->from('Bet\Entity\Bet', 'bet')
            ->where('bet.successful = :successful')
            ->setParameters(array('successful' => $successful));

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }

    /**
     * Persists a given bet and bankroll.
     *
     * @throws Exception
     * @todo need logging & graceful handling of exceptions
     */
    protected function persistBetAndBankroll(Bet $bet, Bankroll $bankroll) {
        $this->em->getConnection()->beginTransaction();

        try {
            $this->em->persist($bet);
            $this->em->persist($bankroll);
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
            throw $e;
        }
    }
} 