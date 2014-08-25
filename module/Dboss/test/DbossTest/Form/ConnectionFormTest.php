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
     */
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

    /**
     * 
     */
    public function testAddConnectionId()
    {
        $form = new ConnectionForm();
        $form->addConnectionId();

        $this->assertSame(
            true,
            $form->has('connection_id'),
            'connection_id field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddUserId()
    {
        $form = new ConnectionForm();
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
    public function testAddDisplayName()
    {
        $form = new ConnectionForm();
        $form->addDisplayName();

        $this->assertSame(
            true,
            $form->has('display_name'),
            'display_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddDriver()
    {
        $form = new ConnectionForm();
        $form->addDriver();

        $this->assertSame(
            true,
            $form->has('driver'),
            'driver field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddHost()
    {
        $form = new ConnectionForm();
        $form->addHost();

        $this->assertSame(
            true,
            $form->has('host'),
            'host field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddDatabaseName()
    {
        $form = new ConnectionForm();
        $form->addDatabaseName();

        $this->assertSame(
            true,
            $form->has('database_name'),
            'database_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddUserName()
    {
        $form = new ConnectionForm();
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
    public function testAddPassword()
    {
        $form = new ConnectionForm();
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
        $form = new ConnectionForm();
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
        $form = new ConnectionForm();
        $form->addSubmit();

        $this->assertSame(
            true,
            $form->has('save_submit'),
            'save_submit field missing from form'
        );
    }
}