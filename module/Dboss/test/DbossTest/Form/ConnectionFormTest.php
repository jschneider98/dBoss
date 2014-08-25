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

    /**
     * 
     **/
    public function testSetup()
    {
        $form = new ConnectionForm();
        $form->setup();

        $this->assertSame(
            11,
            $form->count(),
            "Incorrect number of form elements"
        );
    }
}