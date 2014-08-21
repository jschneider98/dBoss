<?php

namespace DbossTest\Mock;

class Adapter
{
    public $platform;

    public function init(array $params = array())
    {
        $platform = null;

        extract($params, EXTR_IF_EXISTS);

        $this->platform = $platform;
    }
}