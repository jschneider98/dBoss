<?php

/**
 * Null factory. Returns Null resource objects.
 */

namespace Dboss\Schema\Resource;

class ResourceFactory extends ResourceFactoryAbstract
{
    /**
     * Returns a resource for a specific db platform (based on object's resource type)
     * 
     * @return obj A resource object
     */
    public function getResource()
    {
        return new Null(array('db' => $this->db));
    }

    /**
     * Returns all resources for a specific db platform
     * 
     * @return array An array of all resource objects
     */
    public function getAllResources()
    {
        return array(
            new Null(array('db' => $this->db))
        );
    }
}