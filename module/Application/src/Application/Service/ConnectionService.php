<?php

namespace Application\Service;

class ConnectionService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Application\Entity\Connection';
        parent::__construct($params);
    }
}