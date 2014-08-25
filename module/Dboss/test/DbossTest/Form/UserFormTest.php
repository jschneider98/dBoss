<?php

namespace DbossTest\Form;

use Dboss\Form\UserForm;
use PHPUnit_Framework_TestCase;

class UserFormTest extends PHPUnit_Framework_TestCase
{
    protected $form;

    /**
     * 
     */
    public function setUp()
    {
        $this->form = new UserForm();
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
            8,
            $this->form->count(),
            "Incorrect number of form elements"
        );
    }

    /**
     * 
     */
    public function testAddUserId()
    {
        $this->form->addUserId();

        $this->assertSame(
            true,
            $this->form->has('user_id'),
            'user_id field missing from form'
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
    public function testAddRoleId()
    {
        $this->form->addRoleId();

        $this->assertSame(
            true,
            $this->form->has('role_id'),
            'role_id field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddFirstName()
    {
        $this->form->addFirstName();

        $this->assertSame(
            true,
            $this->form->has('first_name'),
            'first_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddLastName()
    {
        $this->form->addLastName();

        $this->assertSame(
            true,
            $this->form->has('last_name'),
            'last_name field missing from form'
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

    /**
     * 
     */
    public function testAddVerifyPasswrod()
    {
        $this->form->addVerifyPassword();

        $this->assertSame(
            true,
            $this->form->has('verify_password'),
            'verify_password field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddSubmit()
    {
        $this->form->addSubmit();

        $this->assertSame(
            true,
            $this->form->has('save_submit'),
            'save_submit field missing from form'
        );
    }
}
