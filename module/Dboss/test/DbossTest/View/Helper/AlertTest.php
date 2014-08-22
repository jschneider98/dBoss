<?php
namespace DbossTest\View\Helper;

use Dboss\View\Helper\Alert;
use PHPUnit_Framework_TestCase;

class AlertTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testInvokeMsg()
    {
        $alert = new Alert();

        $result = $alert->__invoke("test", "info", "unit_test_title");

        $this->assertSame(
            true,
            is_string($result),
            "Returned value should be a string"
        );
    }

    /**
     * 
     */
    public function testInvokeArray()
    {
        $alert = new Alert();

        $result = $alert->__invoke(array("test"), "info", "unit_test_title");

        $this->assertSame(
            true,
            is_string($result),
            "Returned value should be a string"
        );
    }
}