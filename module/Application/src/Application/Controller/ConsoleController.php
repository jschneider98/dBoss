<?php
/**
 * Console Controller
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Db\Adapter\Adapter;
use RuntimeException;


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

        echo "\nYou're console controller is working correctly. Good job.\n\n";
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

        echo "Done.\n";
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
}
