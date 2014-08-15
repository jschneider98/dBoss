<?php

/**
 * Schema Resource Abstract
 */

namespace Dboss\Schema\Resource;

abstract class ResourceAbstract
{
    protected $resource_type;
    protected $resource_list;
    protected $db;

    public function __construct(array $params = array())
    {
        $db = NULL;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $db) {
            throw new Exception("Database connection object is required in " . __METHOD__);
        }

        $this->db = $db;
    }

    /**
     * Does resource support schemas?
     * @TODO: Should be part of the db platform?
     */
    public function supportsSchemas()
    {
        return true;
    }

    /**
     *  Get the resource_type
     **/
    public function getResourceType()
    {
        return $this->resource_type;
    }

    /**
     * Gets an array of resources indexed by a unique encoded_id
     **/
    public function getEncodedResourceList(array $params = array())
    {
        if ( ! $this->resource_list) {
            $this->getResourceList($params);
        }

        $encoded_resource_list = array();

        foreach ($this->resource_list as $row) {
            $display_argument_part = "";
            $argument_part = "";
            $schema_part = "";
            $display_schema_part = "";
            $table_part = "";
            $display_table_part = "";

            if (isset($row['schema_name'])) {
                $schema_part = $row['schema_name'] . "_";
                $display_schema_part = $row['schema_name'] . ".";
            }

            if (isset($row['table_name'])) {
                $table_part = $row['table_name'] . "_";
                $display_table_part = $row['table_name'] . ".";
            }

            if (isset($row['resource_arguments'])) {
                $argument_part = "_" . str_replace(" ", "_", $row['resource_arguments']);
                $argument_part = str_replace(",", "", $argument_part);

                $display_argument_part = "(" . $row['resource_arguments'] . ")";
            }

            $row['display'] = $display_schema_part . $display_table_part . $row['resource_name'] . $display_argument_part;

            $encoded_id = 
                "resource_" . $row['resource_type'] . "_" 
                . $schema_part 
                . $table_part 
                . $row['resource_name']
                . $argument_part;
                
            $encoded_resource_list[$encoded_id] =  $row;
        }
        
        return $encoded_resource_list;
    }

    /**
     * Gets the SQL for listing the resource values
     **/ 
    public abstract function getResourceListSql(array $params = array());

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

            if (count($conditional_parts) > 2) {
                $where .= "\nAND LOWER(schema_name) LIKE " . strtolower($platform->quoteValue($conditional_parts[0]));
                $where .= "\nAND LOWER(table_name) LIKE " . strtolower($platform->quoteValue($conditional_parts[1]));
                $where .= "\nAND LOWER(resource_name) LIKE " . strtolower($platform->quoteValue("%" . $conditional_parts[2] . "%"));
            } else if (count($conditional_parts) > 1) {
                $where .= "\nAND LOWER(schema_name) LIKE " . strtolower($platform->quoteValue($conditional_parts[0]));
                $where .= "\nAND LOWER(resource_name) LIKE " . strtolower($platform->quoteValue("%" . $conditional_parts[1] . "%"));
            } else {
                $where .= "\nAND LOWER(resource_name) LIKE " . strtolower($platform->quoteValue("%" . $search . "%"));
            }
        }

        return $where;
    }

    /**
     * Get the ordery by condtion for the resource list SQL
     **/
    public function getOrderBy(array $params = array())
    {
        //$platform = $this->db->getPlatform();

        if ($this->supportsSchemas()) {
            $order_by = "\nORDER BY schema_name, resource_name";
        } else {
            $order_by = "\nORDER BY resource_name";
        }

        return $order_by;
    }

    /**
     * Get the SELECT query for a given table name. Only applicable to Table resources
     **/
    public function getSelectSql(array $params = array())
    {
        return "This feature is not supported by this resource";
    }

    /**
     * Get the INSERT query for a given table name. Only applicable to Table resources
     **/
    public function getInsertSql(array $params = array())
    {
        return "This feature is not supported by this resource";
    }

    /**
     * Get the UPDATE query for a given table name. Only applicable to Table resources
     **/
    public function getUpdateSql(array $params = array())
    {
        return "This feature is not supported by this resource";
    }

    /**
     * Get the DELETE query for a given table name. Only applicable to Table resources
     **/
    public function getDeleteSql(array $params = array())
    {
        return "This feature is not supported by this resource";
    }

    /**
     * Return a list of query statements that have at least these fields: 
     *  schema_name, resource_name, resource_type, resource_arguments
     *  These fields MUST be returned even if the DB platform/resource doesn't support them 
     *  (NULL values should be returned if the fields are not supported).
     *  A resource_name is the name of the resource based on resource type (table name, view name, etc)
     **/
    public function getResourceList(array $params = array())
    {
        $sql = $this->getResourceListSql($params);
        
        $statement = $this->db->query($sql);
        $this->resource_list = $statement->execute();

        return $this->resource_list;
    }

    /**
     * Gets the SQL definition for the resource
     **/
    public abstract function getResourceDefinition(array $params = array());
}