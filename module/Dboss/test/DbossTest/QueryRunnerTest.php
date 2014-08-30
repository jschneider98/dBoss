<?php
namespace DbossTest;

use Dboss\QueryRunner;
use PHPUnit_Framework_TestCase;

class QueryRunnerTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testContructNoUser()
    {
        $this->setExpectedException('\Exception');
        $runner = new QueryRunner(array());
    }

    /**
     *
     */
    public function testContructNoQueryService()
    {
        $this->setExpectedException('\Exception');
        $runner = new QueryRunner(array('user' => true));
    }

    /**
     *
     */
    public function testContruct()
    {
        $this->setExpectedException('\Exception');
        $runner = new QueryRunner(
            array(
                'user'          => true,
                'query_service' => ture,
            )
        );
    }
}