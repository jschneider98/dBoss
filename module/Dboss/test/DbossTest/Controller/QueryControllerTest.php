<?php
namespace DbossTest\Controller;

use DbossTest\Bootstrap;
use DbossTest\Mock\Adapter;
use DbossTest\Mock\Platform;
use DbossTest\Mock\Statement;
use Dboss\Controller\QueryController;
use Dboss\Entity\User;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use PHPUnit_Framework_TestCase;



class QueryControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new QueryController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    /**
     * 
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $this->controller->user = true;
        $this->controller->db = true;

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 
     */
    public function testHistoryAction()
    {
        $this->routeMatch->setParam('action', 'history');

        $this->controller->user = new User();
        $this->controller->user->user_id = 1;
        $this->controller->db = true;

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 
     */
    public function testSavedAction()
    {
        $this->routeMatch->setParam('action', 'saved');

        $this->controller->user = new User();
        $this->controller->user->user_id = 1;
        $this->controller->db = true;

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 
     */
    public function testGetSql()
    {
        $platform = new Platform();
        $platform->name = 'PostgreSQL';

        $statement = new Statement();

        $results = array("Test SQL");
        $statement->results = $results;

        $adapter = new Adapter();
        $adapter->platform = $platform;
        $adapter->statement = $statement;

        $this->controller->user = true;
        $this->controller->db = $adapter;

        $params = array(
            'query_type'    => "Select",
            'schema_name'   => "test",
            'resource_name' => "test",
        );

        $result = $this->controller->getSql($params);

        $this->assertSame(
            true,
            is_string($result),
            "Result should be a string"
        );
    }
}