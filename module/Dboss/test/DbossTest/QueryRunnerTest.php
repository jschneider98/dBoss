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
        $runner = new QueryRunner(
            array(
                'user'          => true,
                'query_service' => true,
            )
        );
    }

    /**
     *
     */
    public function testExecSqlNoSql()
    {
        $runner = new QueryRunner(
            array(
                'user'          => true,
                'query_service' => true,
            )
        );

        $result = $runner->execSql();

        $this->assertSame(
            false,
            $result,
            'Result should be false'
        );
    }
}