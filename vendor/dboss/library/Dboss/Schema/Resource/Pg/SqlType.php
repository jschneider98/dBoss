<?php

/**
 * Schema Resource "Type" (custom types)
 **/

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlType extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        
        $this->resource_type = "type";
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
                    object_schema as schema_name,
                    object_name as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM information_schema.data_type_privileges
                WHERE object_type = 'USER-DEFINED TYPE'
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

        $sql = "
            SELECT 
                att.attname as field_name,
                a.typname as type_name,
                a.typinput as type_input
            FROM pg_type t 
            JOIN pg_class ON (reltype = t.oid)
            JOIN pg_catalog.pg_namespace n ON n.oid = t.typnamespace
            JOIN pg_attribute att ON (attrelid = pg_class.oid) 
            JOIN pg_type a ON (atttypid = a.oid) 
            WHERE n.nspname = '$schema_name'
                AND t.typname = '$resource_name'
            ORDER BY att.attnum
        ";
        
        $results = $this->db->query($sql)->execute();

        $definition = "-- DROP TYPE $schema_name.$resource_name;\n\n";
        $definition .= "CREATE TYPE $schema_name.$resource_name AS (";

        $row_count = 0;

        foreach ($results as $row) {
            $row_count++;

            if ($row['type_input'] == "array_in") {
                // remove leading underscore
                $row['type_name'] = substr($row['type_name'], 1);
                $row['type_name'] .= "[]";
            }

            $definition .= "\n\t" . $row['field_name'] . " " . $row['type_name'];

            if ($row_count < count($results)) {
                $definition .= ",";
            }
        }

        $definition .= "\n);";

        return $definition;
    }
}