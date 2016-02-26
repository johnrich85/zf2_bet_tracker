<?php

namespace Bet\Service;

use Bankroll\Entity\Bankroll;
use Bet\Entity\Bet;
use Bet\Repository\BetRepository;
use Bankroll\Repository\BankrollRepository;
use Application\AppClasses\Service as TaService;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;
use Doctrine\ORM\EntityRepository;

class BetService extends TaService\TaService implements PaginatationProviderInterface {

    use PaginatorProviderTrait;

    /**
     * @var int
     */
    protected $userId = 1;

    /**
     * @var \Bet\Repository\BetRepository
     */
    protected $betRepository;

    /**
     * @var \Bankroll\Service\BankrollService
     */
    protected $bankrollService;

    /**
     * Constructor
     */
    public function __construct($betRepository, $bankrollService) {
        $this->betRepository = $betRepository;
        $this->bankrollService = $bankrollService;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getEntryForm($id = null) {
        $this->form = $this->sm->get('BetEntryForm');

        if (!$id) {
            return $this->form;
        }

        if ($bet = $this->em->find('Bet\Entity\Bet', $id) ) {
            $this->form->bind($bet);
        }

        return $this->form;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getDeleteForm($id = null) {
        $this->form = $this->sm
            ->get('BetDeleteForm');

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
        if (!$this->form) $this->getEntryForm();

        $bet = $this->sm->get('BetEntity');

        $this->form->setInputFilter($bet->getInputFilter());
        $this->form->setData($data);

        if ($this->form->isValid()) {
            $bet->exchangeArray($this->form->getData());
            $betValue = $bet->calculateProfitOrLoss();

            $bankroll = $this->bankrollService
                ->amendAmount($this->userId, $betValue);

            $this->persistTransactional($bet, $bankroll);

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

            $bankroll = $this->bankrollService
                ->amendAmount($this->userId, $difference);

            $this->persistTransactional($bet, $bankroll);

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
    public function persist(\Bet\Entity\Bet $bet) {
        try {
            $this->em->persist($bet);
            $this->em->flush();
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        }
    }

    /**
     * @param Bet $bet
     * @param Bankroll $bankroll
     */
    protected function persistTransactional(Bet $bet, Bankroll $bankroll) {
        $this->em->getConnection()->beginTransaction();

        $this->persist($bet);

        $this->bankrollService->persist($bankroll);

        $this->em->getConnection()->commit();
    }

    /**
     * @param $page
     * @param $params
     * @return \Zend\Paginator\Paginator
     * @throws \Exception
     */
    public function getPaginatedList($page, $params) {
        $query = $this->betRepository
            ->QueryBuilderFindBy($params);

        $paginator = $this->getPaginator($query);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
} 