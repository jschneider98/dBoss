<?php

namespace DbossTest\Mock;

class Adapter
{
    public $platform;
    public $statement;

    public function init(array $params = array())
    {
        $platform = null;
        $statement = null;

        extract($params, EXTR_IF_EXISTS);

        $this->platform = $platform;
        $this->statement = $statement;
    }

    /**
     * 
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * 
     */
    public function query($sql)
    {
        if ($this->statement) {
            return $this->statement;
        }
        
        return new Statement($sql);
    }
}