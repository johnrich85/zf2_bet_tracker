<?php

namespace Bet\Service;

use Application\AppClasses\Service as TaService;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;


class BetService extends TaService\TaService implements PaginatationProviderInterface {

    use PaginatorProviderTrait;

    //TODO: remove once user login in implemented.
    protected $userId = 1;

    public function getRepository() {
        return $this->em->getRepository('Bet\Entity\Bet');
    }

    public function getList() {
        $this->getRepository()->findAll();
    }

    public function getPaginatedList($page, $params) {

        $query = $this->getRepository()->QueryBuilderFindBy($params);

        $paginator = $this->getPaginator($query);
        $paginator->setCurrentPageNumber($page);

        return $paginator;

    }

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

    public function create($data) {

        if ( !$this->form ) $this->getEntryForm();

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
                throw $e;
            }

            return true;
        }

        return false;
    }

    public function update($data) {

        if ( !$this->form ) $this->getEntryForm();

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
                throw $e;
            }

            return true;
        }

        return false;
    }

    public function getBetCount($successful) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('count(bet)')
            ->from('Bet\Entity\Bet', 'bet')
            ->where('bet.successful = :successful')
            ->setParameters(array('successful' => $successful));

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }


} 