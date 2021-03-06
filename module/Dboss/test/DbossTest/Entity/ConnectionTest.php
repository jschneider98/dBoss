<?php
namespace DbossTest\Entity;

use Dboss\Entity\Connection;
use DbossTest\Mock\Adapter;
use DbossTest\Mock\Platform;
use DbossTest\Mock\Statement;
use PHPUnit_Framework_TestCase;

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testConnectionInitialState()
    {
        $connection = new Connection();

        $this->assertNull(
            $connection->connection_id,
            "connection_id should initially be null"
        );

        $this->assertNull(
            $connection->user_id,
            "user_id should initially be null"
        );

        $this->assertNull(
            $connection->display_name,
            "display_name should initially be null"
        );

        $this->assertNull(
            $connection->database_name,
            "database_name should initially be null"
        );

        $this->assertNull(
            $connection->user_name,
            "user_name should initially be null"
        );

        $this->assertNull(
            $connection->password,
            "password should initially be null"
        );

        $this->assertNull(
            $connection->host,
            "host should initially be null"
        );

        $this->assertNull(
            $connection->driver,
            "driver should initially be null"
        );

        $this->assertNull(
            $connection->is_server_connection,
            "is_server_connection should initially be null"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $connection = new Connection();

        $data  = array(
            "connection_id"        => "connection_id",
            "user_id"              => "user_id",
            "display_name"         => "display_name",
            "database_name"        => "database_name",
            "user_name"            => "user_name",
            "password"             => "password",
            "host"                 => "host",
            "driver"               => "driver",
            "is_server_connection" => "is_server_connection",
        );

        $connection->exchangeArray($data);

        $this->assertSame(
            $data['connection_id'],
            $connection->connection_id,
            "connection_id was not set correctly"
        );

        $this->assertSame(
            $data['user_id'],
            $connection->user_id,
            "user_id was not set correctly"
        );

        $this->assertSame(
            $data['display_name'],
            $connection->display_name,
            "display_name was not set correctly"
        );

        $this->assertSame(
            $data['database_name'],
            $connection->database_name,
            "database_name was not set correctly"
        );

        $this->assertSame(
            $data['user_name'],
            $connection->user_name,
            "user_name was not set correctly"
        );

        $this->assertSame(
            $data['password'],
            $connection->password,
            "password was not set correctly"
        );

        $this->assertSame(
            $data['host'],
            $connection->host,
            "host was not set correctly"
        );

        $this->assertSame(
            $data['driver'],
            $connection->driver,
            "driver was not set correctly"
        );

        $this->assertSame(
            $data['is_server_connection'],
            $connection->is_server_connection,
            "is_server_connection was not set correctly"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $connection = new Connection();

        $data  = array(
            "connection_id"        => "connection_id",
            "user_id"              => "user_id",
            "display_name"         => "display_name",
            "database_name"        => "database_name",
            "user_name"            => "user_name",
            "password"             => "password",
            "host"                 => "host",
            "driver"               => "driver",
            "is_server_connection" => "is_server_connection",
        );

        $connection->exchangeArray($data);
        $connection->exchangeArray(array());

        $this->assertNull(
            $connection->connection_id,
            "connection_id should initially be null"
        );

        $this->assertNull(
            $connection->user_id,
            "user_id should initially be null"
        );

        $this->assertNull(
            $connection->display_name,
            "display_name should initially be null"
        );

        $this->assertNull(
            $connection->database_name,
            "database_name should initially be null"
        );

        $this->assertNull(
            $connection->user_name,
            "user_name should initially be null"
        );

        $this->assertNull(
            $connection->password,
            "password should initially be null"
        );

        $this->assertNull(
            $connection->host,
            "host should initially be null"
        );

        $this->assertNull(
            $connection->driver,
            "driver should initially be null"
        );

        $this->assertNull(
            $connection->is_server_connection,
            "is_server_connection should initially be null"
        );
    }

    /**
     * 
     */
    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $connection = new Connection();

        $original_data  = array(
            "connection_id"        => "connection_id",
            "user_id"              => "user_id",
            "display_name"         => "display_name",
            "database_name"        => "database_name",
            "user_name"            => "user_name",
            "password"             => "password",
            "host"                 => "host",
            "driver"               => "driver",
            "is_server_connection" => "is_server_connection",
        );

        $connection->exchangeArray($original_data);
        $data = $connection->getArrayCopy();

        $this->assertSame(
            $data['connection_id'],
            $connection->connection_id,
            "connection_id was not set correctly"
        );

        $this->assertSame(
            $data['user_id'],
            $connection->user_id,
            "user_id was not set correctly"
        );

        $this->assertSame(
            $data['display_name'],
            $connection->display_name,
            "display_name was not set correctly"
        );

        $this->assertSame(
            $data['database_name'],
            $connection->database_name,
            "database_name was not set correctly"
        );

        $this->assertSame(
            $data['user_name'],
            $connection->user_name,
            "user_name was not set correctly"
        );

        $this->assertSame(
            $data['password'],
            $connection->password,
            "password was not set correctly"
        );

        $this->assertSame(
            $data['host'],
            $connection->host,
            "host was not set correctly"
        );

        $this->assertSame(
            $data['driver'],
            $connection->driver,
            "driver was not set correctly"
        );

        $this->assertSame(
            $data['is_server_connection'],
            $connection->is_server_connection,
            "is_server_connection was not set correctly"
        );
    }

    /**
     * 
     */
    public function testSetInputFilterFails()
    {
        $connection = new Connection();
        $input_filter = $connection->getInputFilter();
        
        $this->setExpectedException("\Exception");
        $connection->setInputFilter($input_filter);
    }

    /**
     * 
     */
    public function testInputFiltersAreSetCorrectly()
    {
        $connection = new Connection();

        $input_filter = $connection->getInputFilter();

        $this->assertSame(9, $input_filter->count());

        $this->assertTrue($input_filter->has('connection_id'));
        $this->assertTrue($input_filter->has('user_id'));
        $this->assertTrue($input_filter->has('display_name'));
        $this->assertTrue($input_filter->has('database_name'));
        $this->assertTrue($input_filter->has('user_name'));
        $this->assertTrue($input_filter->has('password'));
        $this->assertTrue($input_filter->has('host'));
        $this->assertTrue($input_filter->has('driver'));
        $this->assertTrue($input_filter->has('is_server_connection'));
    }

    /**
     * 
     */
    public function testConnect()
    {
        $connection = new Connection();
        $factory_mock = $this->getMock('\Dboss\Connection\ConnectionFactory');

        $factory_mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue(true));

        $connection->connection_factory = $factory_mock;

        $this->assertSame(
            true,
            $connection->connect(),
            "Connection test failed"
        );
    }

    /**
     * 
     */
    public function testGetDatabaseNamesNullAdapter()
    {
        $connection = new Connection();
        $connection->is_server_connection = true;

        $platform = new Platform();
        $adapter = new Adapter();
        $adapter->platform = $platform;

        $factory_mock = $this->getMock('\Dboss\Connection\ConnectionFactory');

        $factory_mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($adapter));

        $connection->connection_factory = $factory_mock;

        $this->assertSame(
            array(),
            $connection->getDatabaseNames(),
            "Null adapter should return an empty array"
        );
    }

    /**
     * 
     */
    public function testGetDatabaseNamesNoResults()
    {
        $connection = new Connection();
        $connection->is_server_connection = true;

        $platform = new Platform();
        $platform->name = 'PostgreSQL';

        $adapter = new Adapter();
        $adapter->platform = $platform;

        $factory_mock = $this->getMock('\Dboss\Connection\ConnectionFactory');

        $factory_mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($adapter));

        $connection->connection_factory = $factory_mock;

        $this->assertSame(
            array(),
            $connection->getDatabaseNames(),
            "No results should return an empty array"
        );
    }

    /**
     * 
     */
    public function testGetDatabaseNamesWithResults()
    {
        $connection = new Connection();
        $connection->is_server_connection = true;
        $database_name = 'unit_test_db';

        $platform = new Platform();
        $platform->name = 'PostgreSQL';

        $statement = new Statement();

        $results = array(
            array(
                'resource_type' => 'unit_test_type',
                'resource_name' => $database_name,
            )
        );

        $statement->results = $results;

        $adapter = new Adapter();
        $adapter->platform = $platform;
        $adapter->statement = $statement;

        $factory_mock = $this->getMock('\Dboss\Connection\ConnectionFactory');

        $factory_mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($adapter));

        $connection->connection_factory = $factory_mock;

        $this->assertSame(
            array($database_name),
            $connection->getDatabaseNames(),
            "Results did not match"
        );
    }
}