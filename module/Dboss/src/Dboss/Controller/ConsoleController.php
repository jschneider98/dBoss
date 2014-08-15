<?php
/**
 * Console Controller
 */

namespace Dboss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Db\Adapter\Adapter;
use RuntimeException;

// @TEMP
use Dboss\Model\User;

class ConsoleController extends AbstractActionController
{
    /**
     * Simple console test action
     **/
    public function indexAction()
    {
        $request = $this->getRequest();

        if ( ! $request instanceof ConsoleRequest){
            throw new RuntimeException('FATAL ERROR: Tried to use console action in a non-console context');
        }

        $user_service = $this->getServiceLocator()->get('Dboss\Service\UserService');
        $role_service = $this->getServiceLocator()->get('Dboss\Service\RoleService');
        $query_service = $this->getServiceLocator()->get('Dboss\Service\QueryService');

        $boss_role = $role_service->findOneBy(array('role_name' => 'boss'));
        $normal_role = $role_service->findOneBy(array('role_name' => 'normal'));
        $limited_role = $role_service->findOneBy(array('role_name' => 'limited'));

        $config = $this->getServiceLocator()->get('config');
        $security = $config['security'];
        
        $user = new \Dboss\Entity\User();
        $user->security = $security;
        $user->user_name = 'jschneider';
        $user->first_name = 'James';
        $user->last_name = 'Schneider';
        $user->password = 'test';
        $user->role = $boss_role;

        $user_service->save($user);


        $user = new \Dboss\Entity\User();
        $user->security = $security;
        $user->user_name = 'jdoe';
        $user->first_name = 'Jon';
        $user->last_name = 'Doe';
        $user->password = 'djon';
        $user->role = $normal_role;

        $user_service->save($user);

        $user = new \Dboss\Entity\User();
        $user->security = $security;
        $user->user_name = 'jadoe';
        $user->first_name = 'Jane';
        $user->last_name = 'Doe';
        $user->password = 'djon';
        $user->role = $limited_role;

        $user_service->save($user);
        /*
        */

        /*
        $om = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $config = $this->getServiceLocator()->get('config');
        $security = $config['security'];

        $role = new \Dboss\Entity\Role();

        $role->role_level = 1;
        $role->role_name = "boss";
        $role->display_name = "Boss";
        $om->persist($role);
        

        $om->persist($user);

        $query1 = new \Dboss\Entity\Query();
        $query2 = new \Dboss\Entity\Query();

        $query1->query_name = "Query1";
        $query1->query = "SELECT 1";
        $query1->query_hash = md5("SELECT 1");
        $query1->user = $user;
        $om->persist($query1);

        $query2->query_name = "Query2";
        $query2->query = "SELECT 2";
        $query2->query_hash = md5("SELECT 2");
        $query2->user = $user;
        $om->persist($query2);

        $user->queries->add($query1);
        $user->queries->add($query2);

        $om->flush();
        */

        /*
        $user = $user_service->findOneBy(array('first_name' => 'James'));

        $query1 = new \Dboss\Entity\Query();
        $query2 = new \Dboss\Entity\Query();

        $query1->query_name = "Query1";
        $query1->query = "SELECT 1";
        $query1->query_hash = md5("SELECT 1");
        $query1->user = $user;
        $query_service->save($query1);

        $query2->query_name = "Query2";
        $query2->query = "SELECT 2";
        $query2->query_hash = md5("SELECT 2");
        $query2->user = $user;
        $query_service->save($query2);

        echo "Boss: " . $user->isaBoss() . "\n";
        echo "Limited: " . $user->isLimited() . "\n";
        echo "Q1: " . $user->isMyQuery(1) . "\n";
        echo "Q2: " . $user->isMyQuery(2) . "\n";
        echo "Q999: " . $user->isMyQuery(999) . "\n";

        $query = $user->getMyQuery(1);
        echo "Q: " . $query->query . "\n";
        */
    }

    /**
     * 
     **/
    public function loadSqliteAction()
    {
        $request = $this->getRequest();

        if ( ! $request instanceof ConsoleRequest){
            throw new RuntimeException('FATAL ERROR: Tried to use console action in a non-console context');
        }

        $unlink = $request->getParam('unlink', false) || $request->getParam('u', false);
        $with_data = $request->getParam('withdata', false) || $request->getParam('wd', false);

        echo "Loading sqlite schema...\n";

        $config = $this->getServiceLocator()->get('config');
        $system_db_file = $config['db']['database'];
        $file_exists = file_exists($system_db_file);

        if ($file_exists && $unlink) {
            echo "Unlinking system db file...";
            unlink($system_db_file);
            echo "done.\n";
        } elseif ($file_exists) {
            throw new RuntimeException('FATAL ERROR: System database exists and unlink option is set to false');
        }

        $db = $this->getServiceLocator()->get('db');

        $file = $config['module_dir'] . "/script/schema.sqlite.sql";
        $schema_sql = file_get_contents($file);

        $queries = explode(";", $schema_sql);

        foreach ($queries as $query) {
            $statement = $db->query($query);
            $statement->execute();
        }

        echo "Schema load complete.\n";

        if ($with_data) {
            $this->loadData();
        }
    }

    /**
     * 
     **/
    public function listTablesAction()
    {
        $count = 0;
        $request = $this->getRequest();

        if ( ! $request instanceof ConsoleRequest){
            throw new RuntimeException('FATAL ERROR: Tried to use console action in a non-console context');
        }

        echo "\nListing tables in system db.\n";

        $db = $this->getServiceLocator()->get('db');

        $sql = "
            SELECT name
            FROM sqlite_master
            WHERE type = 'table'
                AND name != 'sqlite_sequence'
            ORDER BY name
        ";

        $statement = $db->query($sql);
        $results = $statement->execute();

        foreach ($results as $result) {
            $count++;
            echo "\n$count: " . implode(", ", $result);
        }

        echo "\nDone.\n";
    }

    // ************* Non-Action Methods ******************

    /**
     * 
     **/
    protected function loadData()
    {
        $sm = $this->getServiceLocator();
        $config = $sm->get('config');

        $params = array(
            'table' => $sm->get('Dboss\Service\RoleService'),
            'entity' => new \Dboss\Entity\Role(),
            'file_name' => $config['module_dir'] . "/script/roles.txt"
        );

        $this->loadFileData($params);

        $params = array(
            'table' => $sm->get('Dboss\Service\DataTypeService'),
            'entity' => new \Dboss\Entity\DataType(),
            'file_name' => $config['module_dir'] . "/script/data_types.txt"
        );

        $this->loadFileData($params);
    }

    /**
     * 
     **/
    protected function loadFileData(array $params = array())
    {
        $table = null;
        $entity = null;
        $file_name = null;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $table || ! $entity || ! $file_name) {
            throw new RuntimeException('FATAL ERROR: Invalid parameters passed to ' . __METHOD__);
        }

        $fh = fopen($file_name , "r");

        if ( ! $fh) {
            throw new RuntimeException('FATAL ERROR: Could not open file ($file_name) in ' . __METHOD__);
        }

        echo "Loading $file_name data...";

        while (($line = fgets($fh, 4096)) !== false) {
            $parts = explode("\t", $line);

            $data = array();
            foreach ($parts as $part) {
                list($key, $value) = explode("=", $part);
                $data[trim($key)] = trim($value);
            }

            $entity->exchangeArray($data);
            $table->save($entity);

            echo ".";
        }

        echo "done.\n";
        fclose($fh);
    }
}
