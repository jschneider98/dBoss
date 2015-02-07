<?php

/**
 * Schema Resource: Table
 */

namespace Dboss\Schema\Resource\My;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlTable extends ResourceAbstract
{
    /**
     * @param array $params
     * @throws \Dboss\Schema\Resource\Exception
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $this->resource_type = "table";
    }

    /**
     * @param array $params
     * @return string
     */
    public function getResourceListSql(array $params = array())
    {
        $exclude_order_by = false;

        extract($params, EXTR_IF_EXISTS);

        $sql = "
            SELECT *
            FROM (
                SELECT
                    table_schema AS schema_name,
                    CAST(NULL AS char) AS table_name,
                    table_name AS resource_name,
                    CAST(NULL AS char) AS resource_arguments,
                    CAST('{$this->resource_type}' AS char) as resource_type
                FROM information_schema.tables
                WHERE table_schema != 'information_schema'
            ) AS main
        ";
        $sql .= $this->getWhere($params);

        if (! $exclude_order_by) {
            $sql .= $this->getOrderBy($params);
        }

        return $sql;
    }

    /**
     * @param array $params
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function getResourceDefinition(array $params = array())
    {
        $schema_name = null;
        $resource_name = null;
        $resource_arguments = null;

        extract($params, EXTR_IF_EXISTS);

        if (!$schema_name || !$resource_name) {
            throw new \Exception("Invalid resource ({$this->resource_type}) in " . __METHOD__);
        }

        $resource = $schema_name . "." . $resource_name;

        $sql = "SHOW CREATE TABLE {$resource}";
        $results = $this->db->query($sql)->execute();

        $result = $results->current();
        return $result['Create Table'];
    }
}