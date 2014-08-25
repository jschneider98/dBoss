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

    /**
     * 
     */
    public function testAddUserId()
    {
        $form = new UserForm();
        $form->addUserId();

        $this->assertSame(
            true,
            $form->has('user_id'),
            'user_id field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddUserName()
    {
        $form = new UserForm();
        $form->addUserName();

        $this->assertSame(
            true,
            $form->has('user_name'),
            'user_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddRoleId()
    {
        $form = new UserForm();
        $form->addRoleId();

        $this->assertSame(
            true,
            $form->has('role_id'),
            'role_id field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddFirstName()
    {
        $form = new UserForm();
        $form->addFirstName();

        $this->assertSame(
            true,
            $form->has('first_name'),
            'first_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddLastName()
    {
        $form = new UserForm();
        $form->addLastName();

        $this->assertSame(
            true,
            $form->has('last_name'),
            'last_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddPassword()
    {
        $form = new UserForm();
        $form->addPassword();

        $this->assertSame(
            true,
            $form->has('password'),
            'password field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddVerifyPasswrod()
    {
        $form = new UserForm();
        $form->addVerifyPassword();

        $this->assertSame(
            true,
            $form->has('verify_password'),
            'verify_password field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddSubmit()
    {
        $form = new UserForm();
        $form->addSubmit();

        $this->assertSame(
            true,
            $form->has('save_submit'),
            'save_submit field missing from form'
        );
    }
}
