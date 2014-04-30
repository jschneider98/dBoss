<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class DataTypeTable extends AbstractTable
{
    public function __construct(TableGateway $table_gateway)
    {
        $params = array(
            'pkey'          => 'data_type_id',
            'table_gateway' => $table_gateway
        );

        parent::__construct($params);
    }
}