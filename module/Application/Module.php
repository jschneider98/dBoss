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
use Application\Service\UserService;


use Application\Model\Role;
use Application\Model\RoleTable;
use Application\Model\DataType;
use Application\Model\DataTypeTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $event_manager  = $e->getApplication()->getEventManager();
        $module_route_listener = new ModuleRouteListener();
        $module_route_listener->attach($event_manager);

        // Doctrine events
        $service_manager = $e->getApplication()->getServiceManager();
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
                'Application\Service\UserService' => function($sm) {
                    $object_manager = $sm->get('Doctrine\ORM\EntityManager');
                    $params = array('object_manager' => $object_manager);
                    return new UserService($params);
                },

                // @TODO: Remove these because we're switching to doctrine style entities etc
                'Application\Model\UserTable' => function($sm) {
                    $table_gateway = $sm->get('Application\Model\UserTableGateway');
                    return new UserTable($table_gateway);
                },
                'Application\Model\UserTableGateway' => function ($sm) {
                    $db = $sm->get('db');
                    $hydrator = new \Application\Hydrator\TableEntityMapper();
                    $rowset_prototype = new \Application\Model\User;
                    $result_set = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $rowset_prototype);

                    return new TableGateway('user', $db, null, $result_set);
                },

                'Application\Model\RoleTable' => function($sm) {
                    $table_gateway = $sm->get('Application\Model\RoleTableGateway');
                    return new RoleTable($table_gateway);
                },
                'Application\Model\RoleTableGateway' => function ($sm) {
                    $db = $sm->get('db');
                    $hydrator = new \Application\Hydrator\TableEntityMapper();
                    $rowset_prototype = new \Application\Model\Role;
                    $result_set = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $rowset_prototype);

                    return new TableGateway('role', $db, null, $result_set);
                },

                'Application\Model\DataTypeTable' => function($sm) {
                    $table_gateway = $sm->get('Application\Model\DataTypeTableGateway');
                    return new DataTypeTable($table_gateway);
                },
                'Application\Model\DataTypeTableGateway' => function ($sm) {
                    $db = $sm->get('db');
                    $hydrator = new \Application\Hydrator\TableEntityMapper();
                    $rowset_prototype = new \Application\Model\DataType;
                    $result_set = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $rowset_prototype);

                    return new TableGateway('data_type', $db, null, $result_set);
                },
            )
        );
    }
}
