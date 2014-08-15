<?php

namespace Dboss\Model;

abstract class AbstractEntity
{
    /**
     * 
     **/
    public function __construct(array $params = array())
    {
        $data = array();

        extract($params, EXTR_IF_EXISTS);

        $this->exchangeArray($data);
    }

    /**
     * 
     **/
    public function exchangeArray($data = array())
    {
        $properties = $this->getArrayCopy();

        foreach ($properties as $property => $value) {
            $this->$property = (isset($data[$property])) ? $data[$property] : null;
        }
    }

    /**
     * 
     **/
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}