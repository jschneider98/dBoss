<?php

namespace Dboss\Service;

class DataTypeService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Dboss\Entity\DataType';
        parent::__construct($params);
    }
}