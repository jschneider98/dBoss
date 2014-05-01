<?php
/**
 * Console Controller
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Db\Adapter\Adapter;
use RuntimeException;

// @TEMP
use Application\Model\User;

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

        $sm = $this->getServiceLocator();
        $config = $sm->get('config');
        $user = new User(array('security' => $config['security']));
        $user->password = "mypass";
        $user->user_name = "jschneider";
        echo "pass:" . $user->password . "\n";
        echo "raw_user: " . $user->getRawUserName() . "\n";
        echo "user: " . $user->user_name . "\n";

        //$data = $user->getArrayCopy();
        //var_dump($data) . "\n";
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
            'table' => $sm->get('Application\Model\RoleTable'),
            'entity' => new \Application\Model\Role(),
            'file_name' => $config['module_dir'] . "/script/roles.txt"
        );

        $this->loadFileData($params);

        $params = array(
            'table' => $sm->get('Application\Model\DataTypeTable'),
            'entity' => new \Application\Model\DataType(),
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
