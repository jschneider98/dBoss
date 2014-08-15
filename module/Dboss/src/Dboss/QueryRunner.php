<?php

/**
 * Query Runner utility class
 */

namespace Dboss;

use Dboss\SqlFormatter;
use Application\Entity\Query;

class QueryRunner
{
    protected $sql;
    protected $query_name;
    protected $run_in_transaction;
    protected $db;
    protected $query_service;
    protected $results = array();
    protected $user = null;

    protected $errors = array();
    protected $status = array();
    protected $warnings = array();
    
    public function __construct(array $params = array())
    {
        $sql = null;
        $db = null;
        $user = null;
        $query_name = null;
        $query_service = null;
        $multiple_queries = true;
        $run_in_transaction = true;

        extract($params, EXTR_IF_EXISTS);

        if (! $user) {
            throw new \Exception("Invalid user in " . __METHOD__);
        }

        if (! $query_service) {
            throw new \Exception("Invalid query_service in " . __METHOD__);
        }

        $this->user = $user;
        $this->query_name = $query_name;
        $this->sql = $sql;
        $this->db = $db;
        $this->query_service = $query_service;
        $this->multiple_queries = $multiple_queries;
        $this->run_in_transaction = $run_in_transaction;

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
     * @return array An array of results
     */
    public function execSql()
    {
        if ( ! $this->sql) {
            $this->errors[] = "Empty SQL string";
            return false;
        }

        $params = array('force_query_history' => true);
        $this->saveData($params);

        try {
            if ($this->run_in_transaction) {
                $this->begin();
            }

            $queries = $this->getQueries($this->sql);

            foreach ($queries as $query) {
                $query = trim($query);

                if ($query) {
                    $statement = $this->db->query($query);
                    $this->results[] = $statement->execute();
                }
            }

            if ($this->run_in_transaction) {
                $this->commit();
            }

            return $this->results;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();

            if ($this->run_in_transaction) {
                $this->rollback();
            }

            return false;
        }
    }

    /**
     * Process the query data (eg., save query and log for query history)
     */
    public function saveData(array $params = array())
    {
        $force_query_history = false;

        extract($params, EXTR_IF_EXISTS);

        $query_hash = md5($this->sql);

        $data = array(
            'query'      => $this->sql,
            'query_hash' => $query_hash,
        );

        if ($this->query_name && ! $force_query_history) {
            $criteria = array(
                'user_id'    => $this->user->user_id,
                'query_name' => $this->query_name
            );

            $data['query_name'] = $this->query_name;
        }
        else {
            $criteria = array(
                'user_id'    => $this->user->user_id,
                'query_name' => null,
                'query_hash' => $query_hash
            );
        }

        $query = $this->query_service->findOneBy($criteria);

        if (! $query) {
            $query = $this->query_service->create();
        }

        $query->exchangeArray($data);
        $query->user = $this->user;

        $this->query_service->save($query);
    }

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
     * 
     **/
    public function begin()
    {
        $statement = $this->db->query("BEGIN");
        $this->results[] = $statement->execute();
    }

    /**
     * 
     **/
    public function commit()
    {
        $statement = $this->db->query("COMMIT");
        $this->results[] = $statement->execute();
    }

    /**
     * 
     **/
    public function rollback()
    {
        $statement = $this->db->query("ROLLBACK");
        $this->results[] = $statement->execute();
    }
}