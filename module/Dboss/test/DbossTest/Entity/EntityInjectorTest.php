<?php
namespace DbossTest\Entity;

use Zend\ServiceManager\ServiceManager;
use Dboss\Entity\EntityInjector;
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
}