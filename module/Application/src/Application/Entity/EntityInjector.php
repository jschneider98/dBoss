<?php

namespace Application\Entity;

/**
 * Take care of all doctrine entity dependancy injection here
 **/
class EntityInjector
{
    private $service_manager;

    public function __construct($service_manager)
    {
        $this->service_manager = $service_manager;
    }

    public function postLoad($event)
    {
        $entity = $event->getEntity();
        
        if ($entity instanceof User) {
            $config = $this->service_manager->get('config');
            $entity->security = $config['security'];
        }
    }
}