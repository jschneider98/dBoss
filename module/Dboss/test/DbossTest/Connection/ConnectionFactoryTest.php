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
}