<?php

/**
 * Schema Resource "Sequence"
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlSequence extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        
        $this->resource_type = "sequence";
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
                    n.nspname AS schema_name,
                    NULL::text as table_name,
                    c.relname as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM pg_class c, pg_namespace n
                WHERE relkind = 'S' AND n.oid = c.relnamespace AND 
                (n.nspname NOT LIKE 'pg_%' AND n.nspname != 'information_schema')
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

        if ( ! $schema_name || ! $resource_name) {
            throw new Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        $sql = "SELECT * FROM $schema_name.$resource_name";

        $results = $this->db->query($sql)->execute();

        // Should be only one result
        $row = $results->current();

        $definition = "-- DROP SEQUENCE $schema_name.$resource_name;\n\n";

        $definition .= "CREATE SEQUENCE $schema_name.$resource_name\n";
        $definition .= "\tINCREMENT {$row['increment_by']}\n";
        $definition .= "\tMINVALUE {$row['min_value']}\n";
        $definition .= "\tMAXVALUE {$row['max_value']}\n";
        $definition .= "\tSTART {$row['start_value']}\n";
        $definition .= "\tCACHE {$row['cache_value']};\n";

        return $definition;
    }
}