<?php

/**
 * Schema Resource Table
 */

namespace Dboss\Schema\Resource;

use Zend\Db\Adapter\Adapter;

class PgSqlTable extends ResourceAbstract
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        
        $this->resource_type = "table";
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
                    schemaname AS schema_name,
                    tablename AS resource_name,
                    NULL::text as resource_arguments,
                    '{$this->resource_type}'::text as resource_type
                FROM pg_tables
                WHERE schemaname NOT LIKE 'pg_%' AND schemaname != 'information_schema'
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
        $schema_name = null;
        $resource_name = null;
        $resource_arguments = null;

        extract($params, EXTR_IF_EXISTS);
        
        if ( ! $schema_name || ! $resource_name) {
            throw new \Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        $resource = $schema_name . "." . $resource_name;

        $definition = "-- DROP TABLE {$resource};\n\n";
        $definition .= "CREATE TABLE {$resource}\n(\n";

        $sql_parts = array();

        // Fields
        $fields = $this->getFields($params);

        foreach ($fields as $field) {
            $field_def = "\t" . $field['field_name'] . " " . $field['data_type'];

            if ($field['not_null'] == "1") {
                $field_def .= " NOT NULL";
            }

            if ($field['default']) {
                $field_def .= " DEFAULT " . $field['default'];
            }

            $sql_parts[] = $field_def;
        }

        // Constraints
        $constraints = $this->getContraints($params);

        foreach ($constraints as $constraint) {
            $constraint_def = "CONSTRAINT {$constraint['constraint_name']} {$constraint['definition']}";
            $constraint_def = str_replace(" REFERENCES", "\n\tREFERENCES", $constraint_def);
            $constraint_def = str_replace(" ON UPDATE", "\n\tON UPDATE", $constraint_def);

            $sql_parts[] = $constraint_def;
        }

        $definition .= implode(",\n", $sql_parts);
        $definition .= "\n);\n\n";

        // Indexes
        $indexes = $this->getIndexes($params);

        $index_defs = array();

        foreach ($indexes as $index) {
            $index_def = str_replace(" ON ", "\n\tON ", $index['definition']);
            $index_def = str_replace(" USING ", "\n\tUSING ", $index_def);
            $index_def = str_replace(" (", "\n\t(", $index_def);

            $index_defs[] = $index_def;
        }

        if ($index_defs) {
            $definition .= implode(";\n\n", $index_defs) . ";\n\n";
        }

        // Triggers
        $triggers = $this->getTriggers($params);

        $trigger_defs = array();

        foreach ($triggers as $trigger) {
            $trigger_def = str_replace(" ON ", "\n\tON ", $trigger['definition']);
            $trigger_def = str_replace(" AFTER ", "\n\tAFTER ", $trigger_def);
            $trigger_def = str_replace(" FOR ", "\n\tFOR ", $trigger_def);
            $trigger_def = str_replace(" EXECUTE ", "\n\tEXECUTE ", $trigger_def);

            $trigger_defs[] = $trigger_def;
        }

        if ($trigger_defs) {
            $definition .= implode(";\n\n", $trigger_defs) . ";";
        }

        return $definition;
    }

    /**
     * Get table field data
     **/
    public function getFields(array $params = array())
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
            WHERE c.relname = '{$resource_name}'
                AND n.nspname = '{$schema_name}'
                AND a.attnum > 0
                AND NOT a.attisdropped
            ORDER BY a.attnum
        ";

        return $this->db->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * Get table contraints
     **/
    public function getContraints(array $params = array())
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
                con.conname as constraint_name,
                pg_catalog.pg_get_constraintdef(con.oid, true) as definition,
                con.contype as constraint_type
            FROM pg_catalog.pg_class c
            JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
            JOIN pg_catalog.pg_constraint con ON con.conrelid = c.oid
            WHERE c.relname = '{$resource_name}'
                AND n.nspname = '{$schema_name}'
            ORDER BY contype DESC
        ";

        return $this->db->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * Get table indexed
     **/
    public function getIndexes(array $params = array())
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
                c2.relname as index_name,
                pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) as definition
            FROM pg_catalog.pg_class c
            JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
            JOIN pg_catalog.pg_index i ON i.indrelid = c.oid
            JOIN pg_catalog.pg_class c2 ON c2.oid = i.indexrelid
            WHERE c.relname = '{$resource_name}'
                AND n.nspname = '{$schema_name}'
                AND indisprimary IS NOT TRUE
            ORDER BY c2.relname
        ";

        return $this->db->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * Get table triggers
     **/
    public function getTriggers(array $params = array())
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
                t.tgname as trigger_name,
                pg_catalog.pg_get_triggerdef(t.oid) as definition,
                t.tgenabled
            FROM pg_catalog.pg_class c
            JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
            JOIN pg_catalog.pg_trigger t ON t.tgrelid = c.oid
            WHERE c.relname = '{$resource_name}'
                AND n.nspname = '{$schema_name}'
                AND t.tgconstraint = 0
        ";

        return $this->db->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * Get table SELECT query
     **/
    public function getSelectSql(array $params = array())
    {
        $schema_name = null;
        $resource_name = null;
        $with_field_names = false;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $schema_name || ! $resource_name) {
            return "";
        }

        if ( ! $with_field_names) {
            $sql = "SELECT *" .
                "\nFROM $schema_name.$resource_name;";
        } else {
            $sql = "SELECT\n";

            $fields = $this->getFields($params);
            $field_array = array();

            while ($field = $fields->fetch(PDO::FETCH_ASSOC)) {

                $field_array[] = "\t" . $field['field_name'];
            }

            $sql .= implode(",\n", $field_array);
            $sql .= "\nFROM $schema_name.$resource_name;";
        }

        return $sql;
    }

    /**
     * Get table INSERT query
     **/
    public function getInsertSql(array $params = array())
    {
        $schema_name = null;
        $resource_name = null;
        $with_field_names = false;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $schema_name || ! $resource_name) {
            return "";
        }

        $sql = "INSERT INTO $schema_name.$resource_name (\n";

        $fields = $this->getFields($params);
        $field_array = array();
        $place_hodlers = array();

        while ($field = $fields->fetch(PDO::FETCH_ASSOC)) {
            $field_array[] = "\t" . $field['field_name'];
            $place_hodlers[] = "\t:{$field['field_name']}";
        }

        $sql .= implode(",\n", $field_array);
        $sql .= "\n)";
        $sql .= "\nVALUES (\n";
        $sql .= implode(",\n", $place_hodlers);
        $sql .= "\n);";

        return $sql;
    }

    /**
     * Get table UPDATE query
     **/
    public function getUpdateSql(array $params = array())
    {
        $schema_name = null;
        $resource_name = null;
        $with_field_names = false;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $schema_name || ! $resource_name) {
            return "";
        }

        $sql = "UPDATE $schema_name.$resource_name";
        $sql .= "\nSET\n";

        $fields = $this->getFields($params);
        $field_array = array();

        while ($field = $fields->fetch(PDO::FETCH_ASSOC)) {
            $field_array[] = "\t" . $field['field_name'] . " = ?";
        }

        $sql .= implode(",\n", $field_array);
        $sql .= "\nWHERE <condition>;";

        return $sql;
    }

    /**
     * Get table DELETE query
     **/
    public function getDeleteSql(array $params = array())
    {
        $schema_name = null;
        $resource_name = null;
        $with_field_names = false;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $schema_name || ! $resource_name) {
            return "";
        }

        $sql = "DELETE FROM $schema_name.$resource_name";
        $sql .= "\nWHERE <condition>;";

        return $sql;
    }
}