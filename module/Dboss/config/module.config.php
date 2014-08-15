<?php
/**
 * 
 */
return array(
    'module_dir' => dirname(__DIR__),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/home[/connection_string/:connection_string]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action[/user_id/:user_id][/connection_id/:connection_id][/connection_string/:connection_string]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
            ),
            'auth' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/auth[/:action[/connection_string/:connection_string]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'index',
                    ),
                ),
            ),
            'query' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/query[/:action[/query_type/:query_type][/schema_name/:schema_name][/resource_name/:resource_name][/connection_string/:connection_string][/with_field_names/:with_field_names][/query_id/:query_id]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller'    => 'Query',
                        'action'        => 'index',
                    ),
                ),
            ),
            'database' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/database[/connection_string/:connection_string]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller'    => 'Database',
                        'action'        => 'index',
                    ),
                ),
            ),
            'schema' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/schema[/:action[/schema_name/:schema_name][/table_name/:table_name][/resource_name/:resource_name][/resource_arguments/:resource_arguments][/resource_type/:resource_type][/resource_value/:resource_value][/connection_string/:connection_string]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller'    => 'Schema',
                        'action'        => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dboss\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Dboss\Controller\Admin'    => 'Dboss\Controller\AdminController',
            'Dboss\Controller\Auth'     => 'Dboss\Controller\AuthController',
            'Dboss\Controller\Index'    => 'Dboss\Controller\IndexController',
            'Dboss\Controller\Query'    => 'Dboss\Controller\QueryController',
            'Dboss\Controller\Database' => 'Dboss\Controller\DatabaseController',
            'Dboss\Controller\Schema'   => 'Dboss\Controller\SchemaController',
            'Dboss\Controller\Console'  => 'Dboss\Controller\ConsoleController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'     => __DIR__ . '/../view/layout/layout.phtml',
            'dboss/index/index' => __DIR__ . '/../view/dboss/index/index.phtml',
            'error/404'         => __DIR__ . '/../view/error/404.phtml',
            'error/index'       => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'test' => array(
                    'options' => array(
                        'route' => 'test',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Dboss\Controller',
                            'controller'    => 'Console',
                            'action'        => 'index'
                        ),
                    ),
                ),
                'load-sqlite' => array(
                    'options' => array(
                        'route' => 'load-sqlite [--unlink|-u] [--withdata|-wd]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Dboss\Controller',
                            'controller'    => 'Console',
                            'action'        => 'load-sqlite'
                        ),
                    ),
                ),
                'list-tables' => array(
                    'options' => array(
                        'route' => 'list-tables',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Dboss\Controller',
                            'controller'    => 'Console',
                            'action'        => 'list-tables'
                        ),
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Dboss/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Dboss\Entity' => 'application_entities'
                )
            )
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'bootstrapAlert' => 'Dboss\View\Helper\Alert',
        )
    ),
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>',
            'message_separator_string' => '<br>',
            'message_close_string'     => '</div>',
        )
    ),
);
