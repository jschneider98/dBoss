<?php

namespace DbossTest\Form;

use Dboss\Form\UserForm;
use PHPUnit_Framework_TestCase;

class UserFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testUserFormConstruct()
    {
        $form = new UserForm();

        $this->assertSame(
            'post',
            $form->getAttribute('method'),
            'Default form method should be post'
        );
    }

    /**
     * 
     */
    public function testSetup()
    {
        $form = new UserForm();
        $form->setup();

        $this->assertSame(
            8,
            $form->count(),
            "Incorrect number of form elements"
        );
    }
}
