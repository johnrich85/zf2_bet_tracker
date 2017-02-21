<?php namespace Bet\Service;

use Bankroll\Entity\Bankroll;
use Bet\Entity\Bet;
use Application\AppClasses\Service as TaService;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;

use Bet\Hydrator\BetHydrator;
use Bet\Paginator\FallBack;
use Bet\Validator\BetValidator;

/**
 * Class BetService
 *
 * @package Bet\Service
 */
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
     * @var BetHydrator
     */
    protected $hydrator;

    /**
     * BetService constructor.
     *
     * @param $betRepository
     * @param $bankrollService
     * @param BetValidator $validator
     * @param BetHydrator $hydrator
     */
    public function __construct($betRepository, $bankrollService, BetValidator $validator, BetHydrator $hydrator)
    {
        $this->betRepository = $betRepository;
        $this->bankrollService = $bankrollService;
        $this->validator = $validator;
        $this->hydrator = $hydrator;
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

            $this->hydrator->hydrate($data, $bet);

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

        $this->hydrator->hydrate((array) $data, $bet);

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
        return $this->betRepository->find($id);
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
     */
    public function getBetCount($successful)
    {
        return $this->betRepository
            ->getBetCountByStatus($successful);
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

        return new FallBack($result, $numRows, $take);
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

        $query = $this->betRepository
            ->allByMonth($skip, $take, $params);

        $result = $query->getQuery()->getResult();

        $numRows = $this->betRepository->totalRows($query);

        return new FallBack($result, $numRows, $take);
    }
}