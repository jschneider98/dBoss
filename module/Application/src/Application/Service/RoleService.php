<?php

namespace Application\Service;

class RoleService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Application\Entity\Role';
        parent::__construct($params);
    }
}