<?php

namespace DbossTest\Mock;

class Statement
{
    public $sql;
    public $results = array();

    public function init(array $params = array())
    {
        $sql = null;
        $results = array();

        extract($params, EXTR_IF_EXISTS);

        $this->sql = $sql;
        $this->results = $results;
    }

    /**
     * 
     */
    public function execute()
    {
        return $this->results;
    }
}