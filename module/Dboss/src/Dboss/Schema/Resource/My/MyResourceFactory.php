<?php

/**
 * Factory that generates schema resource objects based on resource type
 */

namespace Dboss\Schema\Resource\My;

use Dboss\Schema\Resource\ResourceFactoryAbstract;
use Dboss\Schema\Resource\NullResource;

class MyResourceFactory extends ResourceFactoryAbstract
{
    /**
     * Returns a resource for a specific db platform (based on object's resource type)
     *
     * @return obj A resource object
     */
    public function getResource()
    {
        $params = array('db' => $this->db);

        if ($this->db->platform->getName() == "MySQL") {
            switch ($this->resource_type) {
                case "column":
                case "columns":
                case "col":
                case "cols":
                    return new NullResource($params);
                    break;
                case "view":
                case "views":
                    return new NullResource($params);
                    break;
                case "sch":
                case "schema":
                case "schemas":
                    return new NullResource($params);
                    break;
                case "func":
                case "function":
                case "functions":
                    return new NullResource($params);
                    break;
                case "fkey":
                    return new NullResource($params);
                    break;
                case "seq":
                case "sequence":
                case "sequences":
                    return new NullResource($params);
                    break;
                case "type":
                case "types":
                    return new NullResource($params);
                    break;
                case "db":
                case "database":
                case "databases":
                    return new NullResource($params);
                    break;
                case "trig":
                case "trigger":
                case "triggers":
                    return new NullResource($params);
                    break;
                case "idx":
                case "indx":
                case "index":
                case "indexes":
                    return new NullResource($params);
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

        return new NullResource($params);
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
        );
    }
}
