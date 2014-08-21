<?php

namespace Dboss\Service;

use Dboss\Connection\ConnectionFactory;

class ConnectionService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Dboss\Entity\Connection';
        parent::__construct($params);
    }

    /**
     * 
     **/
    public function create()
    {
        $entity = parent::create();
        $entity->connection_factory = new ConnectionFactory(array('connection' => $entity));

        return $entity;
    }
}