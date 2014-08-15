<?php

namespace Dboss\Service;

class UserService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Dboss\Entity\User';
        parent::__construct($params);

        $security = null;

        extract($params, EXTR_IF_EXISTS);

        if (! $security || ! is_array($security) || ! isset($security['salt_key'])) {
            throw new \Exception("Invalid security passed to " . __METHOD__);
        }

        $this->security = $security;
    }

    /**
     * 
     **/
    public function findActiveUsers()
    {
        return $this->getRepository()->findActiveUsers();
    }

    /**
     * 
     **/
    public function findInactiveUsers()
    {
        return $this->getRepository()->findInactiveUsers();
    }

    /**
     * 
     **/
    public function create()
    {
        $entity_name = $this->getRepository()->getClassName();
        $entity = new $entity_name();
        $entity->security = $this->security;

        return $entity;
    }
}