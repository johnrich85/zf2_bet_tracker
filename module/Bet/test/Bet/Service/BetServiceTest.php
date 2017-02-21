<?php
namespace Bet\Test\Service;
use Zend\ServiceManager\ServiceManager;
use BetTest\Bootstrap;
use \Bet\Service\BetService;

class BetServiceTest extends \PHPUnit_Framework_TestCase  {

    protected $serviceManager;

    public function setUp() {
        $this->serviceManager = Bootstrap::getServiceManager();
    }

    public function getBetServiceInstance() {
        $entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');

        $betRepositoryMock = $this->getMockBuilder('Bet\Repository\BetRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $bankrollRepositoryMock = $this->getMockBuilder('Bankroll\Repository\BankrollRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $BetService = new BetService(
            $betRepositoryMock,
            $bankrollRepositoryMock
        );
        $BetService->setServiceManager($this->serviceManager);
        $BetService->setEntityManager($entityManager);

        return $BetService;
    }
}