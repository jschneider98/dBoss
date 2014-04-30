<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable extends AbstractTable
{
    public function __construct(TableGateway $table_gateway)
    {
        $params = array(
            'pkey'          => 'user_id',
            'table_gateway' => $table_gateway
        );

        parent::__construct($params);
    }
}