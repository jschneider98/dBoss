<?php
namespace Dboss\Form;

use Zend\Form\Form;

class ConnectionForm extends Form
{
    public function __construct()
    {
        parent::__construct('user_form');
        $this->setAttribute('method', 'post');
    }

    /**
     * 
     **/
    public function setup()
    {
        $this->addConnectionId();
        $this->addUserId();
        $this->addDisplayName();
        $this->addDriver();
        $this->addHost();
        $this->addDatabaseName();
        $this->addUserName();
        $this->addPassword();
        $this->addVerifyPassword();
        $this->addIsServerConnection();
        $this->addSubmit();
    }

    /**
     * 
     **/
    public function addConnectionId()
    {
        $this->add(array(
            'name' => 'connection_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
    }

    /**
     * 
     **/
    public function addUserId()
    {
        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
    }

    /**
     * 
     **/
    public function addDisplayName()
    {
        $label = 'Display Name';

        $this->add(array(
            'name' => 'display_name',
            'attributes' => array(
                'type'        => 'text',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addDriver()
    {
        $label = 'Driver';

        $this->add(array(
            'name' => 'driver',
            'attributes' => array(
                'type'        => 'text',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addHost()
    {
        $label = 'Host';

        $this->add(array(
            'name' => 'host',
            'attributes' => array(
                'type'        => 'text',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addDatabaseName()
    {
        $label = 'Database Name';

        $this->add(array(
            'name' => 'database_name',
            'attributes' => array(
                'type'        => 'text',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addUserName()
    {
        $label = 'User Name';

        $this->add(array(
            'name' => 'user_name',
            'attributes' => array(
                'type'        => 'text',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addPassword()
    {
        $label = 'Password';

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'        => 'password',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addVerifyPassword()
    {
        $label = 'Verify Password';

        $this->add(array(
            'name' => 'verify_password',
            'attributes' => array(
                'type'        => 'password',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addIsServerConnection()
    {
        $label = 'Is Server Connection';

        $this->add(array(
            'name' => 'is_server_connection',
            'attributes' => array(
                'type'        => 'text',
                'placeholder' => $label,
                'class'       => "form-control input-lg",
            ),
            'options' => array(
                'label'            => $label,
                'label_attributes' => array("style" => "width: 100%"),
            ),
        ));
    }

    /**
     * 
     **/
    public function addSubmit()
    {
        $this->add(array(
            'name' => 'save_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id'    => 'save_button',
                'class' => "btn btn-success btn-lg",
            ),
        ));
    }
}