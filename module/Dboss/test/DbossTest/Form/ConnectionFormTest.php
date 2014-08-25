<?php

namespace DbossTest\Form;

use Dboss\Form\ConnectionForm;
use PHPUnit_Framework_TestCase;

class ConnectionFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testConnectionFormConstruct()
    {
        $form = new ConnectionForm();

        $this->assertSame(
            'post',
            $form->getAttribute('method'),
            'Default form method should be post'
        );
    }
}