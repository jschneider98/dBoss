<?php

/**
 * Factory that generates schema resource objects based on resource type and db platform
 */

namespace Dboss\Schema\Resource;

use Dboss\Schema\Resource\Pg\PgResourceFactory;

class ResourceFactory extends ResourceFactoryAbstract
{
    /**
     * Returns the resource for a specific db platform
     * 
     * @return obj A resource object
     */
    public function getResource()
    {
        return $this->getDbResourceFactory()->getResource();
    }

    /**
     *  Returns all resources for a specific db platform
     * 
     * @return array An array of all resource objects
     */
    public function getAllResources()
    {
        return $this->getDbResourceFactory()->getAllResources();
    }

    /**
     * Get the factory for a specific db platform
     * 
     * @return obj A resource factory object
     */
    public function getDbResourceFactory()
    {
        switch ($this->db->platform->getName()) {
             case 'PostgreSQL':
                 return new PgResourceFactory($this->params);
                 break;
             
             default:
                 return new NullResourceFactory($this->params);
                 break;
         }
    }
}