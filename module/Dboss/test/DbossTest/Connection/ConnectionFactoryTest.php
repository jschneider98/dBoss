<?php
namespace DbossTest\Connection;

use Dboss\Entity\Connection;
use Dboss\Connection\ConnectionFactory;
use DbossTest\Mock\Adapter;
use DbossTest\Mock\Platform;
use DbossTest\Mock\Statement;
use PHPUnit_Framework_TestCase;

class ConnectionFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * 
     */
    public function testGetConnectionNoEntity()
    {
        $factory = new ConnectionFactory();

        $null = $factory->getConnection();

        $this->assertSame(
            null,
            $null,
            "Connection with no entity should return null"
        );
    }

    /**
     * 
     */
    public function testGetConnectionNull()
    {
        $connection = new Connection();
        $factory = new ConnectionFactory(array('connection' => $connection));

        $null = $factory->getConnection();

        $this->assertSame(
            null,
            $null,
            "Connection with no driver should return null"
        );
    }

    /**
     * 
     */
    public function testGetConnectionPgsql()
    {
        $connection = new Connection();
        $connection->driver = 'Pdo_Pgsql';
        $factory = new ConnectionFactory(array('connection' => $connection));

        $adapter = $factory->getConnection();

        $this->assertInstanceOf(
            'Zend\Db\Adapter\Adapter',
            $adapter,
            "Zend DB adapter should be returned by factory"
        );
    }
}