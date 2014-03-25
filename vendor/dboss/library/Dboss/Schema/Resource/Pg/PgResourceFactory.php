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
        // @TEMP
        return new SqlTable(array('db' => $this->db));

        if ($this->db->platform->getName() == "PostgreSQL") {
            switch ($this->resource_type) {
                case "table":
                    return new SqlTable(array('db' => $this->db));
                    break;
                case "view":
                    return new SqlView(array('db' => $this->db));
                    break;
                case "schema":
                    return new SqlSchema(array('db' => $this->db));
                    break;
                case "function":
                    return new SqlFunction(array('db' => $this->db));
                    break;
                case "sequence":
                    return new SqlSequence(array('db' => $this->db));
                    break;
                case "type":
                    return new SqlType(array('db' => $this->db));
                    break;
                case "database":
                    return new SqlDatabase(array('db' => $this->db));
                    break;
                case "everything":
                    return new SqlEverything(array('db' => $this->db));
                    break;
            }
        }
        
        return new Null(array('db' => $this->db));
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
            new SqlType($params)
        );
    }
}