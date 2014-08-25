<?php

namespace DbossTest\Form;

use Dboss\Form\AuthFilter;
use PHPUnit_Framework_TestCase;

class AuthFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testAuthFilter()
    {
        $auth_filter = new AuthFilter();

        $this->assertSame(2, $auth_filter->count());

        $this->assertTrue($auth_filter->has('user_name'));
        $this->assertTrue($auth_filter->has('password'));
    }
}