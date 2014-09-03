<?php

/**
 * Schema Resource "Foreign Key"
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlForeignKey extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "foreign_key";
    }

    /**
     * 
     */
    public function getResourceListSql(array $params = array())
    {
        $exclude_order_by = FALSE;

        extract($params, EXTR_IF_EXISTS);

        $sql = "
            SELECT
                src_schema_name as schema_name,
                src_table_name as table_name,
                constraint_name
                    || ' (' || src_schema_name || '.' || src_table_name || '.' || src_field_name || ' -> '
                    || dest_schema_name || '.' || dest_table_name || '.' || dest_field_name || ')' as resource_name,
                NULL::text as resource_arguments,
                '{$this->resource_type}'::text as resource_type
            FROM (
                SELECT
                    con.conname as constraint_name,
                    pg_catalog.pg_get_constraintdef(con.oid, true) as definition
                FROM pg_catalog.pg_class c
                JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
                JOIN pg_catalog.pg_constraint con ON con.conrelid = c.oid
                WHERE con.contype = 'f'
            ) as main
            JOIN (
                SELECT
                    tc.table_schema as src_schema_name,
                    tc.table_name as src_table_name,
                    pa.attname as src_field_name,
                    cs.nspname as dest_schema_name,
                    ct.relname as dest_table_name,
                    dpa.attname as dest_field_name,
                    pc.conname as constraint_name
                FROM information_schema.table_constraints tc
                JOIN pg_constraint pc ON tc.constraint_name = pc.conname
                JOIN pg_attribute pa ON pa.attnum = ANY (pc.conkey) AND pa.attrelid = pc.conrelid

                JOIN pg_attribute dpa ON dpa.attnum = ANY (pc.confkey) AND dpa.attrelid = pc.confrelid
                JOIN pg_class ct ON ct.oid = pc.confrelid
                JOIN pg_namespace cs ON cs.oid = ct.relnamespace
                WHERE pc.contype = 'f'
            ) as map USING (constraint_name)
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

        $definition = "Not implemented yet";

        return $definition;
    }

    /**
     * Get the where condition for the resource list SQL
     **/
    public function getWhere(array $params = array())
    {
        $search = "";

        extract($params, EXTR_IF_EXISTS);

        $platform = $this->db->getPlatform();

        $where = "\nWHERE 1=1";

        if ($search) {
            $conditional_parts = explode(".", $search);

            if (count($conditional_parts) > 1) {
                $part = array_shift($conditional_parts);
                $where .= "\nAND LOWER(schema_name) LIKE " . strtolower($platform->quoteValue($part));
            }
            
            $part = array_shift($conditional_parts);

            $where .= "\nAND (LOWER(constraint_name) LIKE " 
                . strtolower($platform->quoteValue("%" . $part . "%"))
                . " OR LOWER(definition) LIKE " . strtolower($platform->quoteValue("%" . $part . "%")) . ")";
        }

        return $where;
    }

    /**
     * Get the ordery by condtion for the resource list SQL
     **/
    public function getOrderBy(array $params = array())
    {
        return "\nORDER BY schema_name DESC, constraint_name DESC";
    }
}