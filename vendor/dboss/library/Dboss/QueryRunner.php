<?php

/**
 * Query Runner utility class
 */

namespace Dboss;

use Zend\Db\Adapter\Adapter;
use Dboss\SqlFormatter;

class QueryRunner
{
    protected $sql;
    protected $query_name;
    protected $run_in_transaction;
    protected $db;
    protected $sys_db;
    protected $statements = array();
    protected $user_id = null;

    protected $errors = array();
    protected $status = array();
    protected $warnings = array();
    
    public function __construct(array $params = array()) 
    {
        $sql = null;
        $query_name = null;
        $db = null;
        $sys_db = null;
        $user_id = null;
        $multiple_queries = true;
        $run_in_transaction = true;

        extract($params, EXTR_IF_EXISTS);

        /*
        if ( ! $user_id) {

            throw new Exception("Invalid user_id in " . __METHOD__);
        }
        */
        
        $this->user_id = $user_id;
        $this->query_name = $query_name;
        $this->sql = $sql;
        $this->db = $db;
        $this->sys_db = $sys_db;
        $this->multiple_queries = $multiple_queries;
        $this->run_in_transaction = $run_in_transaction;

        // @TEMP
        $this->run_in_transaction = false;
        /*
        $platform = $db->getDatabasePlatform();

        if ( ! $platform->supportsTransactions()) {

            $this->run_in_transaction = false;
        }
        */
    }

    /**
     * Executes one or more SQL queries
     *
     * @return array An array of Doctrine statement objects on success, false otherwise
     */
    public function execSql()
    {
        if ( ! $this->sql) {
            $this->errors[] = "Empty SQL string";
            return false;
        }

        // $params = array('force_query_history' => true);
        // $this->saveData($params);

        try {
            if ($this->run_in_transaction) {
                $this->db->beginTransaction();
            }

            $queries = $this->getQueries($this->sql);

            foreach ($queries as $query) {
                $query = trim($query);

                if ($query) {
                    $this->statements[] = $this->db->query($query, Adapter::QUERY_MODE_EXECUTE);
                }
            }

            if ($this->run_in_transaction) {
                $this->db->commit();
            }

            return $this->statements;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();

            if ($this->run_in_transaction) {
                $this->db->rollback();
            }

            return false;
        }
    }

    /**
     * Process the query data (eg., save query and log for query history)
     */
    /*
    public function saveData(array $params = array())
    {
        $force_query_history = false;

        extract($params, EXTR_IF_EXISTS);

        $sql_hash = md5($this->sql);

        $data = array(
            'user_id'       => $this->user_id,
            'sql'           => $this->sql,
            'sql_hash'      => $sql_hash,
            'deletion_date' => null
        );

        if ($this->query_name && ! $force_query_history) {
        
            $where = "user_id = {$this->user_id} AND query_name = " . $this->sys_db->quote($this->query_name);
            $data['query_name'] = $this->query_name;
        }
        else {

            $where = "user_id = {$this->user_id} AND query_name IS null AND sql_hash = " . $this->sys_db->quote($sql_hash);
        }

        $this->admitQuery($data, $where);
    }
    */

    /**
     * Parses object's SQL into one or more queries
     *
     * @return array An array of sql query strings
     */
    protected function getQueries($sql)
    {
        if ($this->multiple_queries) {
            return SqlFormatter::splitQuery($sql);
        } else {
            return array($sql);
        }
    }

    /**
     * Statements getter 
     *
     * @return array An array statement objects
     */
    public function getStatements()
    {
        return $this->statements;
    }

    /**
     * Retrieve errors
     *
     * @return array An array of error messages (strings)
     **/
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get statement object's fields
     *
     * @var string
     **/
    public static function getStatementFields($statement)
    {
        $fields = array();

        if ($statement->columnCount() > 0) {
            foreach(range(0, $statement->columnCount() - 1) as $column_index) {
                $fields[] = $statement->getColumnMeta($column_index);
            }
        }

        return $fields;
    }

    /**
     * Get the count of the number of results of a query
     * (Needed because PDO rowCount doesn't always know how many records are returned by a SELECT)
     * 
     * @param object PDO statement
     * @param object PDO connection
     * @return int Count of rows affected or returned
     **/
    public static function getCount($statement, $db)
    {
        $row_count = $statement->rowCount();

        if ($row_count > 0) {
            return $row_count;
        }

        // Only run a SELECT count(*) on platforms that don't 
        // return number of results from SELECT queries
        switch (strtolower($db->getDriver()->getName())) {
            case "pdo_pgsql":
                return 0;
                break;
            default:
                if (self::isSelect($statement)) {
                    $sql = "
                        SELECT count(*) as count
                        FROM (
                            {$statement->queryString}
                        ) as statement_count
                    ";

                    $statement = $db->query($sql);

                    // Should only ever be one row
                    $row = $statement->fetch(PDO::FETCH_ASSOC);

                    return $row['count'];
                }
                break;
        }

        return 0;
    }

    /**
     * Determine if the query statement is a "SELECT" query
     * 
     * @param object PDO statement object
     * @return bool True if statement is a "SELECT", false otherwise
     **/
    public static function isSelect($statement)
    {
        return (stripos(trim($statement->queryString), "select") === 0) ? true : false;
    }

    /**
     * Does dynamic insert/update depending on the existence of a record
     *
     * @param array Associative array key = field name, value = field value
     * @param string A string of where conditions (without the 'WHERE' keyword)
     *
     * @return mixed The primary key value
     **/
    /*
    public function admitQuery($data, $where = null)
    {   
        if (empty($data) || !is_array($data)) {
            throw new Exception("Invalid data ($data) passed to " . __METHOD__);
        }

        $query_db_row = Application_Model_DbTable_Row_Query::getOrCreateRow(array('where' => $where));
            
        $query_db_row->setFromArray($data);
        $query_db_row->save();
    }
    */
}