<?php

/**
 * Schema Resource "View"
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlView extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "view";
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
                    schemaname as schema_name,
                    viewname as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM pg_views
                WHERE schemaname NOT LIKE 'pg_%' AND schemaname != 'information_schema'
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
            SELECT definition
            FROM pg_views
            WHERE schemaname = '$schema_name' AND viewname = '$resource_name'
        ";

        $results = $this->db->query($sql)->execute();

        // Should be only one result
        $row = $results->current();
        
        $definition = "-- DROP VIEW $schema_name.$resource_name;\n\n";
        $definition .= "CREATE OR REPLACE VIEW {$schema_name}.{$resource_name} AS \n";
        $definition .= $row['definition'];

        return $definition;
    }
}