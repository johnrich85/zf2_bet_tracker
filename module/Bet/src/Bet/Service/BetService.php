<?php

namespace Bet\Service;

use Application\AppClasses\Service as TaService;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;

class BetService extends TaService\TaService implements PaginatationProviderInterface {

    use PaginatorProviderTrait;

    /**
     * @todo remove once user login in implemented
     * @var int
     */
    protected $userId = 1;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
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

            $bankroll = $this->em->getRepository('Bankroll\Entity\Bankroll')->findOneById($this->userId);
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

        if ( !$bet = $this->em->find('Bet\Entity\Bet', $data->id) ) {
            throw new \Exception("Error, trying to update non-existent Bet with id of: " . $data->id);
        }

        $this->form->setInputFilter($bet->getInputFilter());
        $this->form->setData($data);

        if ( $this->form->isValid() ) {
            $bet->exchangeArray($this->form->getData());
            $betValue = $bet->calculateProfitOrLoss();

            $bankroll = $this->em->getRepository('Bankroll\Entity\Bankroll')->findOneById($this->userId);
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
     * Fetches the Bet repository
     *
     * @return Bet\Repository\BetRepository
     */
    public function getRepository() {
        return $this->em->getRepository('Bet\Entity\Bet');
    }

    /**
     * Returns an array of Bet/Entity objects.
     *
     * @return array[Bet/Entity]
     */
    public function getList() {
        return $this->getRepository()->findAll();
    }

    /**
     * Returns paginated list of bets.
     *
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
     * Given an id, returns bet form populated
     * with data. Given null, returns empty
     * form.
     *
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
} 