<?php

/**
 * Schema Resource "Function"
 **/

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlFunction extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "function";
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
                    pn.nspname as schema_name,
                    p.proname as resource_name,
                    
                    CASE WHEN proallargtypes IS NOT NULL THEN
                        pg_catalog.array_to_string(ARRAY(
                            SELECT
                                CASE
                                    WHEN p.proargmodes[s.i] = 'i' THEN ''
                                    WHEN p.proargmodes[s.i] = 'o' THEN 'OUT '
                                    WHEN p.proargmodes[s.i] = 'b' THEN 'INOUT '
                                END ||
                                CASE
                                    WHEN COALESCE(p.proargnames[s.i], '') = '' THEN ''
                                    ELSE p.proargnames[s.i] || ' ' 
                                    END ||
                                        pg_catalog.format_type(p.proallargtypes[s.i], NULL)
                            FROM pg_catalog.generate_series(1, pg_catalog.array_upper(p.proallargtypes, 1)) AS s(i)), ', ')
                    ELSE
                        pg_catalog.array_to_string(ARRAY(
                            SELECT
                                CASE
                                    WHEN COALESCE(p.proargnames[s.i+1], '') = '' THEN ''
                                    ELSE p.proargnames[s.i+1] || ' '
                                END ||
                                    pg_catalog.format_type(p.proargtypes[s.i], NULL)
                        FROM pg_catalog.generate_series(0, pg_catalog.array_upper(p.proargtypes, 1)) AS s(i)), ', ')
                    END as resource_arguments,

                    '{$this->resource_type}'::text as resource_type
                FROM pg_proc p
                JOIN pg_namespace pn ON pn.oid = p.pronamespace
                WHERE (nspname <> 'pg_catalog' AND nspname <> 'information_schema')
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

        $resource = $schema_name.".".$resource_name;

        $argument_condition = "";

        if ($resource_arguments) {
            $resource .= "(" . $resource_arguments . ")";
            $argument_condition = "AND resource_arguments = '$resource_arguments'";
        }

        $sql = "
            SELECT *
            FROM (
                SELECT
                    n.nspname as schema_name,
                    p.proname as resource_name,
                    
                    CASE WHEN p.proretset THEN 'setof ' ELSE '' END ||
                        pg_catalog.format_type(p.prorettype, NULL) as result_data_type,
                    
                    CASE WHEN proallargtypes IS NOT NULL THEN
                        pg_catalog.array_to_string(ARRAY(
                            SELECT
                                CASE
                                    WHEN p.proargmodes[s.i] = 'i' THEN ''
                                    WHEN p.proargmodes[s.i] = 'o' THEN 'OUT '
                                    WHEN p.proargmodes[s.i] = 'b' THEN 'INOUT '
                                END ||
                                CASE
                                    WHEN COALESCE(p.proargnames[s.i], '') = '' THEN ''
                                    ELSE p.proargnames[s.i] || ' ' 
                                    END ||
                                        pg_catalog.format_type(p.proallargtypes[s.i], NULL)
                            FROM pg_catalog.generate_series(1, pg_catalog.array_upper(p.proallargtypes, 1)) AS s(i)), ', ')
                    ELSE
                        pg_catalog.array_to_string(ARRAY(
                            SELECT
                                CASE
                                    WHEN COALESCE(p.proargnames[s.i+1], '') = '' THEN ''
                                    ELSE p.proargnames[s.i+1] || ' '
                                END ||
                                    pg_catalog.format_type(p.proargtypes[s.i], NULL)
                        FROM pg_catalog.generate_series(0, pg_catalog.array_upper(p.proargtypes, 1)) AS s(i)), ', ')
                    END as resource_arguments,

                    CASE
                        WHEN p.provolatile = 'i' THEN 'IMMUTABLE'
                        WHEN p.provolatile = 's' THEN 'STABLE'
                        WHEN p.provolatile = 'v' THEN 'VOLATILE'
                    END as volatility,
                    pg_catalog.pg_get_userbyid(p.proowner) as owner,
                    l.lanname as language,
                    p.procost as cost,
                    p.prorows as rows,
                    p.prosrc as source_code,
                    pg_catalog.obj_description(p.oid, 'pg_proc') as description
                FROM pg_catalog.pg_proc p
                LEFT JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace
                LEFT JOIN pg_catalog.pg_language l ON l.oid = p.prolang
            ) as main
            WHERE schema_name = '$schema_name'
                AND resource_name = '$resource_name'
                $argument_condition
        ";

        $results = $this->db->query($sql)->execute();

        // Should be only one result
        $row = $results->current();

        $return_sql = "";
        
        $definition = "-- DROP FUNCTION {$resource};\n\n";

        $definition .= "CREATE OR REPLACE FUNCTION {$resource}";
        $definition .= "\nRETURNS {$row['result_data_type']}";

        $definition .= "\nAS";
        $definition .= "\n\$BODY\$";
        $definition .= "\n" . $row['source_code'];
        $definition .= "\n\$BODY\$";
        $definition .= "\nLANGUAGE {$row['language']} {$row['volatility']}";
        $definition .= "\nCOST {$row['cost']}";

        if ($row['rows'] > 0) {
            $definition .= "\nROWS {$row['rows']}";
        }

        $definition .= ";";

        return $definition;
    }
}