<?php

/**
 * Abstract class for dBoss resource factories
 */

namespace Dboss\Schema\Resource;

abstract class ResourceFactoryAbstract
{
    protected $resource_type;
    protected $db;
    protected $params;

    public function __construct(array $params = array())
    {
        $resource_type = 'everything';
        $db = NULL;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $resource_type || ! is_string($resource_type)) {
            throw new \Exception("Invalid resource_type in " . __METHOD__);
        }

        if ( ! $db) {
            throw new \Exception("Database connection object is required in " . __METHOD__);
        }

        $this->resource_type = $resource_type;
        $this->db = $db;
        $this->params = $params;
    }

    public abstract function getResource();
    public abstract function getAllResources();
}