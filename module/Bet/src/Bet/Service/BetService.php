<?php

namespace Bet\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Application\AppInterface\PaginatationProviderInterface;
use Application\AppTraits\PaginatorProviderTrait;


class BetService implements ServiceManagerAwareInterface, PaginatationProviderInterface {

    use PaginatorProviderTrait;

    protected $sm;
    protected $em;

    public function setServiceManager(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;
        return $this;
    }

    public function getServiceManager()
    {
        return $this->sm;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

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

        if ($Bet = $this->em->find('Bet\Entity\Bet', $id) ) {
            $this->form->bind($Bet);
        }

        return $this->form;

    }

    public function create($data) {

        if ( !$this->form ) $this->getEntryForm();

        $Bet = $this->sm->get('BetEntity');
        $this->form->setInputFilter($Bet->getInputFilter());

        $this->form->setData($data);

        if ( $this->form->isValid() ) {

            $Bet->exchangeArray($this->form->getData());
            $this->em->persist($Bet);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function update($data) {

        if ( !$this->form ) $this->getEntryForm();

        if ( !$Bet = $this->em->find('Bet\Entity\Bet', $data->id) ) {
            throw new \Exception("Error, trying to update non-existent Bet with id of: " . $data->id);
        }

        $this->form->setInputFilter($Bet->getInputFilter());
        $this->form->setData($data);

        if ( $this->form->isValid() ) {
            $Bet->exchangeArray($this->form->getData());

            $this->em->persist($Bet);
            $this->em->flush();

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