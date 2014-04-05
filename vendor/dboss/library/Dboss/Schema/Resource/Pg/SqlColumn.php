<?php

/**
 * Schema Resource "Column"
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlColumn extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "column";
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
                    c.relname as table_name,
                    a.attname as resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM pg_catalog.pg_class c
                JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
                JOIN pg_catalog.pg_attribute a ON c.oid = a.attrelid
                WHERE a.attnum > 0
                    AND NOT a.attisdropped
                    AND c.relkind = 'r'
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
        $table_name = NULL;
        $resource_name = NULL;
        $resource_arguments = NULL;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $schema_name || ! $resource_name) {
            throw new Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        $sql = "
            SELECT
                n.nspname as schema_name,
                c.relname as table_name,
                a.attname as field_name,
                pg_catalog.format_type(a.atttypid, a.atttypmod) as data_type,
                (
                    SELECT substring(pg_catalog.pg_get_expr(d.adbin, d.adrelid) for 128)
                    FROM pg_catalog.pg_attrdef d
                    WHERE d.adrelid = a.attrelid AND d.adnum = a.attnum AND a.atthasdef
                ) as default,
                a.attnotnull as not_null,
                a.attnum
            FROM pg_catalog.pg_class c
            JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
            JOIN pg_catalog.pg_attribute a ON c.oid = a.attrelid
            WHERE 1=1
                AND n.nspname = '{$schema_name}'
                AND c.relname = '{$table_name}'
                AND a.attname = '{$resource_name}'
                AND a.attnum > 0
                AND NOT a.attisdropped
        ";

        $results = $this->db->query($sql)->execute();

        // Should be only one result
        $row = $results->current();

        $definition = "-- ALTER TABLE {$schema_name}.{$table_name} DROP COLUMN {$resource_name};\n\n";
        $definition .= "ALTER TABLE {$schema_name}.{$table_name} ADD COLUMN {$resource_name} {$row['data_type']};\n\n";

        if ($row['not_null'] == "1") {
            $definition .= "ALTER TABLE {$schema_name}.{$table_name} ALTER COLUMN {$resource_name} SET NOT NULL;\n\n";
        }

        if ($row['default']) {
            $definition .= "ALTER TABLE {$schema_name}.{$table_name} ALTER COLUMN {$resource_name} SET DEFAULT {$row['default']};\n\n";
        }

        return $definition;
    }

    /**
     * Get the ordery by condtion for the resource list SQL
     **/
    public function getOrderBy(array $params = array())
    {
        return "\nORDER BY schema_name, table_name, resource_name";
    }
}