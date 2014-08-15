<?php

namespace Dboss\Service;

class ConnectionService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Dboss\Entity\Connection';
        parent::__construct($params);
    }
}