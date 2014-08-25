<?php

namespace DbossTest\Form;

use Dboss\Form\AuthForm;
use PHPUnit_Framework_TestCase;

class AuthFormTest extends PHPUnit_Framework_TestCase
{
    protected $form;

    /**
     * 
     */
    public function setUp()
    {
        $this->form = new AuthForm();
    }

    /**
     * 
     */
    public function testFormConstruct()
    {
        $this->assertSame(
            'post',
            $this->form->getAttribute('method'),
            'Default form method should be post'
        );
    }
}