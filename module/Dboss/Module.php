<?php
/**
 * 
 */

namespace Dboss;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Dboss\Entity\EntityInjector;
use Dboss\Service\ConnectionService;
use Dboss\Service\DataTypeService;
use Dboss\Service\QueryService;
use Dboss\Service\RoleService;
use Dboss\Service\UserService;

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
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Dboss\Service\ConnectionService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new ConnectionService($params);
                },
                'Dboss\Service\DataTypeService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new DataTypeService($params);
                },
                'Dboss\Service\QueryService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new QueryService($params);
                },
                'Dboss\Service\RoleService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new RoleService($params);
                },
                'Dboss\Service\UserService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $config = $sm->get('config');

                    $params = array(
                        'object_manager' => $object_manager,
                        'security'       => $config['security'],
                    );

                    return new UserService($params);
                },
            )
        );
    }
}
