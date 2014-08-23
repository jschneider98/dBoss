<?php
namespace DbossTest\Service;

use DbossTest\Bootstrap;
use DbossTest\Mock\BadEntityService;
use Dboss\Service\UserService;

use Zend\Stdlib\Exception;

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

    /**
     * 
     */
    public function testAbstractConstructNoEntityManager()
    {
        $this->setExpectedException('BadMethodCallException');
        $user_service = new UserService(array());
    }

    /**
     * 
     */
    public function testAbstractContructNoEntity()
    {
        $this->setExpectedException('BadMethodCallException');
        
        $object_manager = $this->service_manager->get('Doctrine\ORM\EntityManager');
        
        $params = array(
            'object_manager' => $object_manager,
        );

        $bad_service = new BadEntityService($params);
    }
}