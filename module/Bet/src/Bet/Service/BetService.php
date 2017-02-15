<?php

namespace Bet\Service;

use Bankroll\Entity\Bankroll;
use Bet\Entity\Bet;
use Application\AppClasses\Service as TaService;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;

use Bet\Paginator\FallBack;
use Bet\Validator\BetValidator;

class BetService extends TaService\TaService implements PaginatationProviderInterface
{

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
     * @var BetValidator
     */
    protected $validator;

    /**
     * BetService constructor.
     *
     * @param $betRepository
     * @param $bankrollService
     * @param BetValidator $validator
     */
    public function __construct($betRepository, $bankrollService, BetValidator $validator)
    {
        $this->betRepository = $betRepository;
        $this->bankrollService = $bankrollService;
        $this->validator = $validator;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getEntryForm($id = null)
    {
        $this->form = $this->sm->get('BetEntryForm');

        if (!$id) {
            return $this->form;
        }

        if ($bet = $this->em->find('Bet\Entity\Bet', $id)) {
            $this->form->bind($bet);
        }

        return $this->form;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getDeleteForm($id = null)
    {
        $this->form = $this->sm->get('BetDeleteForm');

        if (!$id) {
            return $this->form;
        }

        if ($bet = $this->em->find('Bet\Entity\Bet', $id)) {
            $this->form->bind($bet);
        }

        return $this->form;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data)
    {
        $bet = $this->sm->get('BetEntity');

        if ($this->validator->isValid($data)) {

            $bet->populate($data);

            $bet->setCalculatedProfit();

            $betValue = $bet->calculateNetProfit();

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
    public function update(Bet $bet, $data)
    {
        $oldPL = $bet->calculateNetProfit();

        $bet->populate((array) $data);

        if ($this->validator->isValid($bet->toArray())) {

            $bet->setCalculatedProfit();

            $difference = $bet->calculateProfitDifference($oldPL);

            $bankroll = $this->bankrollService
                ->amendAmount($this->userId, $difference);

            $this->persistTransactional($bet, $bankroll);

            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->em->find('Bet\Entity\Bet', $id);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->validator->getMessages();
    }

    /**
     * @param $successful
     * @return mixed
     * @todo : move to repo
     */
    public function getBetCount($successful)
    {
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
    public function persist(\Bet\Entity\Bet $bet)
    {
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
    protected function persistTransactional(Bet $bet, Bankroll $bankroll)
    {
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
    public function getPaginatedList($page, $params)
    {
        $query = $this->betRepository->all($params);

        $paginator = $this->getPaginator($query);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

    /**
     * @param $page
     * @param $params
     * @return \Zend\Paginator\Paginator
     */
    public function getBetsByDay($page, $take, $params)
    {
        $skip = ($page -1) * $take;

        $query = $this->betRepository->allByDate($skip, $take, $params);

        $result = $query->getQuery()->getResult();

        $numRows = $this->betRepository->totalRows($query);

        return new FallBack(
            $result,
            $numRows,
            $take
        );
    }

    /**
     * @param $page
     * @param $take
     * @param $params
     * @return FallBack
     */
    public function getBetsByMonth($page, $take, $params)
    {
        $skip = ($page -1) * $take;

        $query = $this->betRepository->allByMonth($skip, $take, $params);

        $result = $query->getQuery()->getResult();

        $numRows = $this->betRepository->totalRows($query);

        return new FallBack(
            $result,
            $numRows,
            $take
        );
    }
}