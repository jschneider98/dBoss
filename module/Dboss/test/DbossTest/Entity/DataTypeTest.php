<?php
namespace DbossTest\Entity;

use Dboss\Entity\DataType;
use PHPUnit_Framework_TestCase;

class DataTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testDataTypeInitialState()
    {
        $data_type = new DataType();

        $this->assertNull(
            $data_type->data_type_id,
            "data_type_id should initially be null"
        );

        $this->assertNull(
            $data_type->name,
            "name should initially be null"
        );

        $this->assertNull(
            $data_type->aliases,
            "aliases should initially be null"
        );

        $this->assertNull(
            $data_type->description,
            "description should initially be null"
        );

        $this->assertNull(
            $data_type->driver,
            "driver should initially be null"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $data_type = new DataType();

        $data  = array(
            "data_type_id" => "data_type_id",
            "name"         => "name",
            "aliases"      => "aliases",
            "description"  => "description",
            "driver"       => "driver",
        );

        $data_type->exchangeArray($data);

        $this->assertSame(
            $data['data_type_id'],
            $data_type->data_type_id,
            "data_type_id was not set correctly"
        );

        $this->assertSame(
            $data['name'],
            $data_type->name,
            "name was not set correctly"
        );

        $this->assertSame(
            $data['aliases'],
            $data_type->aliases,
            "aliases was not set correctly"
        );

        $this->assertSame(
            $data['description'],
            $data_type->description,
            "description was not set correctly"
        );

        $this->assertSame(
            $data['driver'],
            $data_type->driver,
            "driver was not set correctly"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $data_type = new DataType();

        $data  = array(
            "data_type_id" => "data_type_id",
            "name"         => "name",
            "aliases"      => "aliases",
            "description"  => "description",
            "driver"       => "driver",
        );

        $data_type->exchangeArray($data);
        $data_type->exchangeArray(array());

        $this->assertNull(
            $data_type->data_type_id,
            "data_type_id should initially be null"
        );

        $this->assertNull(
            $data_type->name,
            "name should initially be null"
        );

        $this->assertNull(
            $data_type->aliases,
            "aliases should initially be null"
        );

        $this->assertNull(
            $data_type->description,
            "description should initially be null"
        );

        $this->assertNull(
            $data_type->driver,
            "driver should initially be null"
        );
    }

    /**
     * 
     */
    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $data_type = new DataType();

        $original_data  = array(
            "data_type_id" => "data_type_id",
            "name"         => "name",
            "aliases"      => "aliases",
            "description"  => "description",
            "driver"       => "driver",
        );

        $data_type->exchangeArray($original_data);
        $data = $data_type->getArrayCopy();

        $this->assertSame(
            $data['data_type_id'],
            $data_type->data_type_id,
            "data_type_id was not set correctly"
        );

        $this->assertSame(
            $data['name'],
            $data_type->name,
            "name was not set correctly"
        );

        $this->assertSame(
            $data['aliases'],
            $data_type->aliases,
            "aliases was not set correctly"
        );

        $this->assertSame(
            $data['description'],
            $data_type->description,
            "description was not set correctly"
        );

        $this->assertSame(
            $data['driver'],
            $data_type->driver,
            "driver was not set correctly"
        );
    }
}

