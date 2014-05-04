<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * Magic setter
     **/
    public function __set($field_name, $value)
    {
        switch ($field_name) {
            default:
                $this->$field_name = $value;
                break;
        }
    }

    /**
     * Magic getter
     **/
    public function __get($field_name)
    {
        switch ($field_name) {
            default:
                return $this->$field_name;
                break;
        }
    }

    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        if (property_exists($this,'creation_date')) {
            $this->creation_date = new \DateTime("now");
        }

        if (property_exists($this,'modification_date')) {
            $this->modification_date = new \DateTime("now");
        }
    }

    /** @ORM\PreUpdate */
    public function onPreUpdate()
    {
        if (property_exists($this,'modification_date')) {
            $this->modification_date = new \DateTime("now");
        }
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