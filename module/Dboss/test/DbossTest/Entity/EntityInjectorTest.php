<?php
namespace DbossTest\Entity;

use Zend\ServiceManager\ServiceManager;
use Dboss\Entity\EntityInjector;
use Dboss\Entity\User;
use PHPUnit_Framework_TestCase;

class EntityInjectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testSetServiceManager()
    {
        $service_manager = new ServiceManager();
        $entity_injector = new EntityInjector($service_manager);

        $entity_injector->setServiceManager($service_manager);

        $this->assertSame(
            $service_manager,
            $entity_injector->getServiceManager(),
            "Service Manager not set correctly"
        );
    }

    /**
     * 
     */
    public function testPostLoad()
    {
        $security = array(
            "salt_key"        => "unit_test_salt",
            'iteration_count' => 8,
            'portable_hashes' => 0,
        );

        $user = new User();

        $event_mock = $this->getMock(
            'Doctrine\ORM\Event\LifecycleEventArgs',
            array(),
            array(),
            "",
            false
        );

        $event_mock->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($user));

        $sm_mock = $this->getMock('Zend\ServiceManager\ServiceManager');

        $sm_mock->expects($this->any())
            ->method('get')
            ->will($this->returnValue(array('security' => $security)));

        $entity_injector = new EntityInjector($sm_mock);
        $entity_injector->postLoad($event_mock);

        $this->assertSame(
            $security,
            $user->security,
            "Post Load did not properly inject the security value into user entity"
        );
    }
}