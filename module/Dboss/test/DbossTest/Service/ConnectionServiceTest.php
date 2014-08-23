<?php
namespace DbossTest\Service;

use DbossTest\Bootstrap;
use Dboss\Service\ConnectionService;
use PHPUnit_Framework_TestCase;

class ConnectionServiceTest extends PHPUnit_Framework_TestCase
{
    protected $service_manager;

    protected function setUp()
    {
        $this->service_manager = Bootstrap::getServiceManager();
    }

    /**
     * 
     */
    public function testConnectionServiceConstruct()
    {
        $object_manager = $this->service_manager->get('Doctrine\ORM\EntityManager');
        $params = array('object_manager' => $object_manager);
        $connection_service = new ConnectionService($params);
    }

    /**
     * 
     */
    public function testConnectionServiceCreate()
    {
        $object_manager = $this->service_manager->get('Doctrine\ORM\EntityManager');
        
        $params = array(
            'object_manager' => $object_manager,
        );

        $connection_service = new ConnectionService($params);
        $connection = $connection_service->create();

        $this->assertInstanceOf(
            '\Dboss\Entity\Connection',
            $connection,
            "Create should return an entity connection"
        );
    }
}