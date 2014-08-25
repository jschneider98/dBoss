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

    /**
     * 
     */
    public function testSetup()
    {
        $this->form->setup();

        $this->assertSame(
            3,
            $this->form->count(),
            "Incorrect number of form elements"
        );
    }

    /**
     * 
     */
    public function testAddUserName()
    {
        $this->form->addUserName();

        $this->assertSame(
            true,
            $this->form->has('user_name'),
            'user_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddPassword()
    {
        $this->form->addPassword();

        $this->assertSame(
            true,
            $this->form->has('password'),
            'password field missing from form'
        );
    }
}