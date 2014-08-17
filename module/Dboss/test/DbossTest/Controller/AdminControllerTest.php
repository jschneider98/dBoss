<?php
namespace DbossTest\Controller;

use DbossTest\Bootstrap;
use Dboss\Controller\AdminController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use PHPUnit_Framework_TestCase;

class AdminControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $serviceManager;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->controller = new AdminController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $this->serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);
    }

    /**
     * 
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $mock_user = $this->getMock('\Dboss\Entity\User');

        $mock_user->expects($this->any())
            ->method('isLimited')
            ->will($this->returnValue(false));

        $mock_user->expects($this->any())
            ->method('isaBoss')
            ->will($this->returnValue(true));

        $mock_user_service = $this->getMock(
            '\Dboss\Service\UserService',
            array(),
            array(),
            "UserService",
            false
        );

        $mock_user_service->expects($this->any())
            ->method('findActiveUsers')
            ->will($this->returnValue(null));

        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService('\Dboss\Service\UserService', $mock_user_service);


        $this->controller->user = $mock_user;
        $this->controller->db = true;

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}