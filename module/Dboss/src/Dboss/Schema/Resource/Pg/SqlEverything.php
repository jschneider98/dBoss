<?php

/**
 * Schema Resource "Everything"
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceAbstract;

class SqlEverything extends ResourceAbstract
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        
        $this->resource_type = "everything";
    }

    /**
     * 
     */
    public function getResourceListSql(array $params = array())
    {
        $sql = "
            SELECT *
            FROM (
        ";

        $factory_params = array(
            'resource_type' => $this->resource_type,
            'db'            => $this->db
        );

        $resource_factory = new PgResourceFactory($factory_params);
        $resources = $resource_factory->getAllResources();

        $params['exclude_order_by'] = TRUE;
        $resource_sql = array();
        foreach ($resources as $resource) {

            $resource_sql[] = $resource->getResourceListSql($params);
        }

        $sql .= implode("\nUNION\n", $resource_sql);
        $sql .= "\n) as main";
        $sql .= $this->getOrderBy($params);
        
        return $sql;
    }

    public function getResourceDefinition(array $params = array())
    {
        return "";
    }
}