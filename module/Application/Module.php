<?php
/**
 * 
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Application\Entity\EntityInjector;
use Application\Service\ConnectionService;
use Application\Service\DataTypeService;
use Application\Service\QueryService;
use Application\Service\RoleService;
use Application\Service\UserService;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $event_manager  = $event->getApplication()->getEventManager();
        $module_route_listener = new ModuleRouteListener();
        $module_route_listener->attach($event_manager);

        // Doctrine events
        $service_manager = $event->getApplication()->getServiceManager();
        $object_manager = $service_manager->get('Doctrine\ORM\EntityManager');
        $doc_event_manager = $object_manager->getEventManager();

        $doc_event_manager->addEventListener(
            array(\Doctrine\ORM\Events::postLoad),
            new EntityInjector($service_manager)
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Dboss' => __DIR__ . '/../../vendor/dboss/library/Dboss'
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Service\ConnectionService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new ConnectionService($params);
                },
                'Application\Service\DataTypeService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new DataTypeService($params);
                },
                'Application\Service\QueryService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new QueryService($params);
                },
                'Application\Service\RoleService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new RoleService($params);
                },
                'Application\Service\UserService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new UserService($params);
                },
            )
        );
    }
}
