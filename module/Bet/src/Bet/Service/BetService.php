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
     * @todo remove once user login in implemented
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
     * Creates and persists a new bet.
     *
     * @param $data
     * @return bool
     */
    public function create($data) {

        $this->getEntryForm();

        $bet = $this->sm->get('BetEntity');

        $this->form->setInputFilter($bet->getInputFilter());
        $this->form->setData($data);

        if ( $this->form->isValid() ) {

            $bet->exchangeArray($this->form->getData());
            $betValue = $bet->calculateProfitOrLoss();

            $bankroll = $this->bankrollRepository->findOneById($this->userId);
            $bankroll->amendAmount($betValue);

            $this->em->getConnection()->beginTransaction(); // suspend auto-commit
            try {
                $this->em->persist($bet);
                $this->em->persist($bankroll);
                $this->em->flush();
                $this->em->getConnection()->commit();
            } catch (Exception $e) {
                //Todo: need logging & graceful handling of exceptions
                $this->em->getConnection()->rollback();
            }

            return $bet;
        }

        return false;
    }

    /**
     * Updates an existing bet.
     *
     * @param $data array
     * @return bool
     * @throws \Exception
     */
    public function update($data) {

        $this->getEntryForm();

        if ( !$bet = $this->betRepository->find($data->id) ) {
            throw new \Exception("Error, trying to update non-existent Bet with id of: " . $data->id);
        }

        $this->form->setInputFilter($bet->getInputFilter());
        $this->form->setData($data);

        if ( $this->form->isValid() ) {
            $bet->exchangeArray($this->form->getData());
            $betValue = $bet->calculateProfitOrLoss();

            $bankroll = $this->bankrollRepository->findOneById($this->userId);
            $bankroll->amendAmount($betValue);

            $this->em->getConnection()->beginTransaction(); // suspend auto-commit
            try {
                $this->em->persist($bet);
                $this->em->persist($bankroll);
                $this->em->flush();
                $this->em->getConnection()->commit();
            } catch (Exception $e) {
                //Todo: need logging & graceful handling of exceptions
                $this->em->getConnection()->rollback();
            }

            return $bet;
        }

        return false;
    }

    /**
     * Given a value of 1, returns the number of
     * successful bets. Given 0, returns unsuccessful
     * bets.
     *
     * @param $successful int
     * @return mixed
     */
    public function getBetCountByStatus($successful) {
        return $this->betRepository->getBetCountByStatus($successful);
    }

    /**
     * Fetches the Bet repository
     *
     * @return Bet\Repository\BetRepository
     */
    public function getRepository() {
        return $this->betRepository;
    }

    /**
     * Returns an array of Bet/Entity objects.
     *
     * @return array[Bet/Entity]
     */
    public function getList() {
        return $this->betRepository->findAll();
    }

    /**
     * Returns paginated list of bets.
     *
     * @param $page
     * @param $params
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginatedList($page, $params) {

        $query = $this->betRepository->QueryBuilderFindBy($params);

        $paginator = $this->getPaginator($query);
        $paginator->setCurrentPageNumber($page);

        return $paginator;

    }

    /**
     * Given an id, returns bet form populated
     * with data. Given null, returns empty
     * form.
     *
     * @param null $id
     * @return mixed
     */
    public function getEntryForm($id = null) {
        $this->form = $this->sm->get('BetEntryForm');

        if (!$id) {
            return $this->form;
        }

        if ($bet = $this->betRepository->find($id) ) {
            $this->form->bind($bet);
        }

        return $this->form;
    }


    public function getDeleteForm($id) {

        if ($bet = $this->betRepository->find($id) ) {
            $this->form = $this->sm->get('BetDeleteForm');
            $this->form->bind($bet);

            return $this->form;
        }

        return false;
    }
} 