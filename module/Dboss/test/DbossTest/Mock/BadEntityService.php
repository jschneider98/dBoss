<?php

namespace DbossTest\Mock;

class BadEntityService extends \Dboss\Service\AbstractObjectManagerService
{
    /**
     * 
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
    }
}
