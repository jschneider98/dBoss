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
}
