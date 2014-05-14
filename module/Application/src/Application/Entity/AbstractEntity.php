<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * Only the properties that should be hydrated
     **/
    abstract public function getFields();

    /**
     * 
     **/
    public function exchangeArray(array $data = array())
    {
        foreach ($this->getFields() as $field_name) {
            $value = (isset($data[$field_name])) ? $data[$field_name] : null;
            $this->__set($field_name, $value);
        }
    }

    /**
     * 
     **/
    public function getArrayCopy()
    {
        $data = array();

        foreach ($this->getFields() as $field_name) {
            $data[$field_name] = $this->__get($field_name);
        }

        return $data;
    }

    /**
     * Magic setter
     **/
    public function __set($property, $value)
    {
        switch ($property) {
            default:
                $this->$property = $value;
                break;
        }
    }

    /**
     * Magic getter
     **/
    public function __get($property)
    {
        switch ($property) {
            default:
                return $this->$property;
                break;
        }
    }

    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        if (property_exists($this,'creation_date') && ! $this->creation_date) {
            $this->creation_date = new \DateTime("now");
        }

        if (property_exists($this,'modification_date')) {
            $this->modification_date = new \DateTime("now");
        }
    }

    /** @ORM\PreUpdate */
    public function onPreUpdate()
    {
        $this->onPrePersist();
    }

    /**
     * 
     **/
    public function delete()
    {
        if (property_exists($this,'deletion_date')) {
            $this->deletion_date = new \DateTime("now");
        }
    }
}