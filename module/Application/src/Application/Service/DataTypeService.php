<?php

namespace Application\Service;

class DataTypeService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Application\Entity\DataType';
        parent::__construct($params);
    }
}