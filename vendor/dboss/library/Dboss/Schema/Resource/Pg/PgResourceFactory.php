<?php

/**
 * Factory that generates schema resource objects based on resource type
 */

namespace Dboss\Schema\Resource\Pg;

use Dboss\Schema\Resource\ResourceFactoryAbstract;

class PgResourceFactory extends ResourceFactoryAbstract
{
    /**
     * Returns a resource for a specific db platform (based on object's resource type)
     * 
     * @return obj A resource object
     */
    public function getResource()
    {
        $params = array('db' => $this->db);

        if ($this->db->platform->getName() == "PostgreSQL") {
            switch ($this->resource_type) {
                case "view":
                case "views":
                    return new SqlView($params);
                    break;
                case "sch":
                case "schema":
                case "schemas":
                    return new SqlSchema($params);
                    break;
                case "func":
                case "function":
                case "functions":
                    return new SqlFunction($params);
                    break;
                case "seq":
                case "sequence":
                case "sequences":
                    return new SqlSequence($params);
                    break;
                case "type":
                case "types":
                    return new SqlType($params);
                    break;
                case "db":
                case "database":
                case "databases":
                    return new SqlDatabase($params);
                    break;
                case "trig":
                case "trigger":
                case "triggers":
                    return new SqlTrigger($params);
                    break;
                case "idx":
                case "indx":
                case "index":
                case "indexes":
                    return new SqlIndex($params);
                    break;
                case "e":
                case "everything":
                case "all":
                case "boss":
                    return new SqlEverything($params);
                    break;
                case "tbl":
                case "table":
                case "tables":
                default:
                    return new SqlTable($params);
                    break;
            }
        }
        
        return new Null($params);
    }

    /**
     * Returns all resources for a specific db platform
     * 
     * @return array An array of all resource objects
     */
    public function getAllResources()
    {
        $params = array('db' => $this->db);

        return array(
            new SqlTable($params),
            new SqlView($params),
            new SqlSchema($params),
            new SqlFunction($params),
            new SqlSequence($params),
            new SqlType($params),
            new SqlTrigger($params),
            new SqlIndex($params),
        );
    }
}