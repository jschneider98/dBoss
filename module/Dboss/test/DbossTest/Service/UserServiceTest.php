<?php
namespace DbossTest\Service;

use DbossTest\Bootstrap;
use Dboss\Service\UserService;
use PHPUnit_Framework_TestCase;

class UserServiceTest extends PHPUnit_Framework_TestCase
{
    protected $service_manager;

    protected function setUp()
    {
        $this->service_manager = Bootstrap::getServiceManager();
    }

    /**
     * 
     */
    public function testUserServiceConstruct()
    {
        $object_manager = $this->service_manager->get('Doctrine\ORM\EntityManager');
        
        $params = array(
            'object_manager' => $object_manager,
            'security'       => array(
                'salt_key'   => 'unit_test',
            )
        );
        
        $user_service = new UserService($params);
    }

    /**
     * 
     */
    public function testUserServiceConstructInvalidSecurity()
    {
        $object_manager = $this->service_manager->get('Doctrine\ORM\EntityManager');
        
        $params = array(
            'object_manager' => $object_manager,
        );

        $this->setExpectedException("\Exception");
        
        $user_service = new UserService($params);
    }
}