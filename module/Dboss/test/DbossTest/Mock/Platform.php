<?php

namespace DbossTest\Mock;

class Platform
{
    public $name;

    public function init(array $params = array())
    {
        $name = null;

        extract($params, EXTR_IF_EXISTS);

        $this->name = $name;
    }

    /**
     * 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     */
    public function quoteValue($value)
    {
        return "'$value'";
    }
}