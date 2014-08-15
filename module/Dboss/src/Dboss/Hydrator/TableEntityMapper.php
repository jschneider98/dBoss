<?php

namespace Dboss\Hydrator;

use ReflectionMethod;
use Traversable;
use Zend\Stdlib\Exception;
use Zend\Stdlib\Hydrator\AbstractHydrator;
use Zend\Stdlib\Hydrator\HydratorOptionsInterface;

class TableEntityMapper extends AbstractHydrator implements HydratorOptionsInterface
{
    protected $data_map = true;

    /**
     * 
     **/
    public function __construct(array $data_map = array())
    {
        parent::__construct();
        $this->data_map = $data_map;
    }

    /**
     * 
     **/
    public function setOptions($options)
    {
        return $this;
    }

    /**
     * 
     **/
    public function extract($object) {}

    /**
     * 
     **/
    public function hydrate(array $data, $object)
    {
        if ( ! is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects the provided $object to be an object',
                __METHOD__
            ));
        }

        foreach ($data as $property => $value) {

            if ( ! property_exists($object, $property)) {

                if (in_array($property, array_keys($this->data_map))) {
                    $prop = $this->data_map[$property];
                    $object->$prop = $value;
                } else {
                    // unknown properties are skipped deliberately
                }
            } else {
                $object->$property = $value;
            }
        }

        return $object;
    }
}