<?php

namespace Application\Service;

class QueryService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Application\Entity\Query';
        parent::__construct($params);
    }
}