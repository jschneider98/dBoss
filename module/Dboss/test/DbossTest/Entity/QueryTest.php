<?php
namespace DbossTest\Entity;

use Dboss\Entity\Query;
use PHPUnit_Framework_TestCase;

class QueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testQueryInitialState()
    {
        $query = new Query();

        $this->assertNull(
            $query->query_id,
            "query_id should initially be null"
        );

        $this->assertNull(
            $query->user_id,
            "user_id should initially be null"
        );

        $this->assertNull(
            $query->query_name,
            "query_name should initially be null"
        );

        $this->assertNull(
            $query->query,
            "query should initially be null"
        );

        $this->assertNull(
            $query->query_hash,
            "query_hash should initially be null"
        );


    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $query = new Query();

        $data  = array(
            "query_id" => "query_id",
            "user_id" => "user_id",
            "query_name" => "query_name",
            "query" => "query",
            "query_hash" => "query_hash",
        );

        $query->exchangeArray($data);

        $this->assertSame(
            $data['query_id'],
            $query->query_id,
            "query_id was not set correctly"
        );

        $this->assertSame(
            $data['user_id'],
            $query->user_id,
            "user_id was not set correctly"
        );

        $this->assertSame(
            $data['query_name'],
            $query->query_name,
            "query_name was not set correctly"
        );

        $this->assertSame(
            $data['query'],
            $query->query,
            "query was not set correctly"
        );

        $this->assertSame(
            $data['query_hash'],
            $query->query_hash,
            "query_hash was not set correctly"
        );


    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $query = new Query();

        $data  = array(
            "query_id" => "query_id",
            "user_id" => "user_id",
            "query_name" => "query_name",
            "query" => "query",
            "query_hash" => "query_hash",
        );

        $query->exchangeArray($data);
        $query->exchangeArray(array());

        $this->assertNull(
            $query->query_id,
            "query_id should initially be null"
        );

        $this->assertNull(
            $query->user_id,
            "user_id should initially be null"
        );

        $this->assertNull(
            $query->query_name,
            "query_name should initially be null"
        );

        $this->assertNull(
            $query->query,
            "query should initially be null"
        );

        $this->assertNull(
            $query->query_hash,
            "query_hash should initially be null"
        );


    }

    /**
     * 
     */
    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $query = new Query();

        $original_data  = array(
            "query_id" => "query_id",
            "user_id" => "user_id",
            "query_name" => "query_name",
            "query" => "query",
            "query_hash" => "query_hash",
        );

        $query->exchangeArray($original_data);
        $data = $query->getArrayCopy();

        $this->assertSame(
            $data['query_id'],
            $query->query_id,
            "query_id was not set correctly"
        );

        $this->assertSame(
            $data['user_id'],
            $query->user_id,
            "user_id was not set correctly"
        );

        $this->assertSame(
            $data['query_name'],
            $query->query_name,
            "query_name was not set correctly"
        );

        $this->assertSame(
            $data['query'],
            $query->query,
            "query was not set correctly"
        );

        $this->assertSame(
            $data['query_hash'],
            $query->query_hash,
            "query_hash was not set correctly"
        );


    }
}

