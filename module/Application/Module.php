<?php
/**
 * 
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Application\Model\Role;
use Application\Model\RoleTable;
use Application\Model\DataType;
use Application\Model\DataTypeTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
