<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * 
     **/
    public function exchangeArray($data = array())
    {
        $properties = $this->getArrayCopy();

        foreach ($properties as $property => $value) {
            $value = (isset($data[$property])) ? $data[$property] : null;
            $this->__set($property, $value);
        }
    }

    /**
     * 
     **/
    public function getArrayCopy()
    {
        $data = array();
        $properties = get_object_vars($this);

        foreach ($properties as $property => $value) {
            $data[$property] = $this->__get($property);
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