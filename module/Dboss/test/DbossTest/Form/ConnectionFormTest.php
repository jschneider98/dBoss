<?php

namespace DbossTest\Form;

use Dboss\Form\ConnectionForm;
use PHPUnit_Framework_TestCase;

class ConnectionFormTest extends PHPUnit_Framework_TestCase
{
    protected $form;

    /**
     * 
     */
    public function setUp()
    {
        $this->form = new ConnectionForm();
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
            11,
            $this->form->count(),
            "Incorrect number of form elements"
        );
    }

    /**
     * 
     */
    public function testAddConnectionId()
    {
        $this->form->addConnectionId();

        $this->assertSame(
            true,
            $this->form->has('connection_id'),
            'connection_id field missing from form'
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
    public function testAddDisplayName()
    {
        $this->form->addDisplayName();

        $this->assertSame(
            true,
            $this->form->has('display_name'),
            'display_name field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddDriver()
    {
        $this->form->addDriver();

        $this->assertSame(
            true,
            $this->form->has('driver'),
            'driver field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddHost()
    {
        $this->form->addHost();

        $this->assertSame(
            true,
            $this->form->has('host'),
            'host field missing from form'
        );
    }

    /**
     * 
     */
    public function testAddDatabaseName()
    {
        $this->form->addDatabaseName();

        $this->assertSame(
            true,
            $this->form->has('database_name'),
            'database_name field missing from form'
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