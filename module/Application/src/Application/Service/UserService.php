<?php

namespace Application\Service;

class UserService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Application\Entity\User';
        parent::__construct($params);
    }
}