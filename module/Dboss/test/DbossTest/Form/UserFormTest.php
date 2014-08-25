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
}
