<?php

namespace Dboss\Service;

class RoleService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Dboss\Entity\Role';
        parent::__construct($params);
    }
}