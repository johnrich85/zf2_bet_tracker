<?php
namespace Bet\Test\Controller;
use Zend\ServiceManager\ServiceManager;
use BetTest\Bootstrap;
use Zend\Http\Request,
    Zend\Http\Response,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch;

class IndexControllerTest extends \PHPUnit_Framework_TestCase  {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected $betService;
    protected $bankrollService;

    public function setUp() {

        $serviceManager = Bootstrap::getServiceManager();
        $this->betService = $this->getMockBuilder('Bet\Service\BetService')
            ->disableOriginalConstructor()
            ->getMock();
        $this->bankrollService = $this->getMockBuilder('Bankroll\Service\BankrollService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new \Bet\Controller\IndexController(
            $this->betService,
            $this->bankrollService,
            new \Illuminate\Support\MessageBag()
        );
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'bet'));
        $this->event      = new MvcEvent();
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }

    public function testDeleteActionWithId()
    {
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', '1');

        $this->betService->expects($this->once())
            ->method('getDeleteForm')
            ->will($this->returnValue($this->getMock('Bet\Form\DeleteForm')));

        $result = $this->controller->dispatch($this->request);

        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        $vars = $result->getVariables();
        $this->assertTrue(isset($vars['form']));
    }

    public function testDeleteActionWithoutId()
    {
        $this->routeMatch->setParam('action', 'delete');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
}