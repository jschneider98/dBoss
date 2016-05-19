<?php

/**
 * Null factory. Returns Null resource objects.
 */

namespace Dboss\Schema\Resource;

class NullResourceFactory extends ResourceFactoryAbstract
{
    /**
     * Returns a resource for a specific db platform (based on object's resource type)
     *
     * @return obj A resource object
     */
    public function getResource()
    {
        return new NullResource(array('db' => $this->db));
    }

    /**
     * Returns all resources for a specific db platform
     *
     * @return array An array of all resource objects
     */
    public function getAllResources()
    {
        return array(
            new NullResource(array('db' => $this->db))
        );
    }
}
