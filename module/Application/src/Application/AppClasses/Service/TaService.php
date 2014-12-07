<?php

namespace Application\AppClasses\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Application\AppTraits\PaginatorProviderTrait;


class TaService implements ServiceManagerAwareInterface {

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

} 