<?php

namespace Bet\Service;

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
     * @var \Bankroll\Repository\BankrollRepository
     */
    protected $bankrollRepository;

    /**
     * Constructor
     */
    public function __construct($betRepository, $bankrollRepository) {
        $this->betRepository = $betRepository;
        $this->bankrollRepository = $bankrollRepository;
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

            $bankroll = $this->bankrollRepository
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

            $bankroll = $this->bankrollRepository
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
    protected function persistBetAndBankroll(\Bet\Entity\Bet $bet, \Bankroll\Entity\Bankroll $bankroll) {
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