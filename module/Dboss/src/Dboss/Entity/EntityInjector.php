<?php

namespace Dboss\Entity;

use Zend\ServiceManager\ServiceManager;
use Dboss\Connection\ConnectionFactory;

/**
 * Take care of all doctrine entity dependancy injection here
 **/
class EntityInjector
{
    protected $service_manager;

    /**
     * 
     */
    public function __construct(ServiceManager $service_manager)
    {
        $this->setServiceManager($service_manager);
    }

    /**
     * 
     */
    public function postLoad($event)
    {
        $entity = $event->getEntity();
        
        if ($entity instanceof User) {
            $config = $this->service_manager->get('config');
            $entity->security = $config['security'];
        } else if ($entity instanceof Connection) {
            $entity->connection_factory = new ConnectionFactory(array('connection' => $entity));
        }
    }

    /**
     * service_manager setter
     * 
     * @param \Zend\ServiceManager\ServiceManager Service Manager
     * @return void
     */
    public function setServiceManager(ServiceManager $service_manager)
    {
        $this->service_manager = $service_manager;
    }

    /**
     * service_manager getter
     * 
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->service_manager;
    }
}