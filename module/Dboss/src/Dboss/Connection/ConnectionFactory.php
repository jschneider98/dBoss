<?php

/**
 * Factory that generates connectoin resource objects based on db driver
 */

namespace Dboss\Connection;

use Zend\Db\Adapter\Adapter;

class ConnectionFactory
{
    public $connection;

    /**
     * @param array $params
     */
    public function __construct($params = array())
    {
        $connection = null;

        extract($params, EXTR_IF_EXISTS);

        $this->connection = $connection;
    }

    /**
     * Returns a zf2 adapter object based on pdo driver
     * 
     * @return obj An zf2 adapter object
     */
    public function getConnection()
    {
        if (! $this->connection) {
            return null;
        }

        switch ($this->connection->driver) {
            case 'Pdo_Pgsql':
            case 'Pdo_Mysql':
                return $this->getGenericConnection();
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Get a generic connection
     * @return Adapter zf2 adapter object
     */
    protected function getGenericConnection()
    {
        $params = array(
            'driver'   => $this->connection->driver,
            'database' => $this->connection->database_name,
            'hostname' => $this->connection->host,
            'username' => $this->connection->user_name,
            'password' => $this->connection->password,
        );

        return new Adapter($params);
    }
}