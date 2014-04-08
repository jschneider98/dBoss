<?php

/**
 * Schema Resource "Index"
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlIndex extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "index";
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
                SELECT
                    n.nspname as schema_name,
                    NULL::text as table_name,
                    c.relname as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM pg_catalog.pg_class c
                JOIN pg_catalog.pg_index i ON c.oid = i.indexrelid
                JOIN pg_namespace n ON n.oid = c.relnamespace
                WHERE n.nspname NOT LIKE 'pg_%' AND n.nspname != 'information_schema'
            ) as main
        ";
        $sql .= $this->getWhere($params);

        if ( ! $exclude_order_by) {
            $sql .= $this->getOrderBy($params);
        }
        
        $this->resource_list = $this->db->query($sql)->execute();

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

        if ( ! $schema_name || ! $resource_name) {
            throw new Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        $sql = "
            SELECT
                n.nspname as schema_name,
                c.relname as resource_name,
                pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) as definition
            FROM pg_catalog.pg_class c
            JOIN pg_catalog.pg_index i ON c.oid = i.indexrelid
            JOIN pg_namespace n ON n.oid = c.relnamespace
            WHERE n.nspname = '$schema_name' AND c.relname = '$resource_name'
        ";

        $results = $this->db->query($sql)->execute();

        // Should be only one result
        $row = $results->current();

        return $row['definition'] . ";\n";
    }
}