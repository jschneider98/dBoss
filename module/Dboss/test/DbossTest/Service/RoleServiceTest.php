<?php
namespace DbossTest\Service;

//use Dboss\Service\RoleService;

use DbossTest\Bootstrap;
use Dboss\Service\RoleService;
use PHPUnit_Framework_TestCase;

class RoleServiceTest extends PHPUnit_Framework_TestCase
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
        $role_service = new RoleService($params);
    }
}