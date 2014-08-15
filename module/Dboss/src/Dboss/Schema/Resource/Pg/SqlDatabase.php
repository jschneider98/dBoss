<?php

/**
 * Schema Resource "Database"
 **/

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlDatabase extends ResourceAbstract
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "database";
    }

    public function getResourceListSql(array $params = array())
    {
        $exclude_order_by = false;

        extract($params, EXTR_IF_EXISTS);
        
        $sql = "
            SELECT *
            FROM (
                SELECT
                    NULL::text as schema_name,
                    datname as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM pg_database
            ) as main
        ";
        $sql .= $this->getWhere($params);

        if (! $exclude_order_by) {
            $sql .= $this->getOrderBy($params);
        }

        $this->resource_list = $this->db->query($sql)->execute();
        
        return $sql;
    }

    public function getResourceDefinition(array $params = array())
    {
        $schema_name = null;
        $resource_name = null;
        $resource_arguments = null;

        extract($params, EXTR_IF_EXISTS);

        if (! $resource_name) {
            throw new Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        // @TODO: Get owner, encoding, etc
        $sql = "-- DROP DATABASE $resource_name;\n\n";
        $sql .= "CREATE DATABASE $resource_name;";

        return $sql;
    }
}