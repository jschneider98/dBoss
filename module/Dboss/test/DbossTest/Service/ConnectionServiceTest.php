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
    public function testRoleServiceConstruct()
    {
        $object_manager = $this->service_manager->get('Doctrine\ORM\EntityManager');
        $params = array('object_manager' => $object_manager);
        $data_type_service = new ConnectionService($params);
    }
}