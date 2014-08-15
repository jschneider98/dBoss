<?php

/**
 * Schema Resource "Schema"
 **/

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlSchema extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "schema";
    }

    /**
     * 
     */
    public function getResourceListSql(array $params = array())
    {
        $exclude_order_by = FALSE;

        extract($params, EXTR_IF_EXISTS);
        
        $sql = "
            SELECT *
            FROM (
                SELECT DISTINCT
                    table_schema as schema_name,
                    NULL::text as table_name,
                    table_schema as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM information_schema.tables 
                WHERE table_type = 'BASE TABLE' 
                    AND table_schema NOT IN ('pg_catalog', 'information_schema')
            ) as main
        ";
        $sql .= $this->getWhere($params);

        if ( ! $exclude_order_by) {
            $sql .= $this->getOrderBy($params);
        }
        
        return $sql;
    }

    /**
     * 
     */
    public function getResourceDefinition(array $params = array())
    {
        $schema_name = NULL;
        $resource_name = NULL;
        $resource_arguments = NULL;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $resource_name) {
            throw new Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        $sql = "-- DROP SCHEMA $resource_name;\n\n";
        $sql .= "CREATE SCHEMA $resource_name;";

        return $sql;
    }
}