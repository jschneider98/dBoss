<?php

namespace Dboss\Model;

use Zend\Db\TableGateway\TableGateway;

class RoleTable extends AbstractTable
{
    public function __construct(TableGateway $table_gateway)
    {
        $params = array(
            'pkey'          => 'role_id',
            'table_gateway' => $table_gateway
        );

        parent::__construct($params);
    }
}