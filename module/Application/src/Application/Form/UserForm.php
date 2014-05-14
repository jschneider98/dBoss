<?php
namespace Application\Form;

use Zend\Form\Form;

class UserForm extends Form
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
        $this->addUserId();
        $this->addUserName();
        $this->addRoleId();
        $this->addFirstName();
        $this->addLastName();
        $this->addFirstName();
        $this->addPassword();
        $this->addVerifyPassword();
        $this->addSubmit();
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
    public function addRoleId()
    {
        $label = 'Role';

        $this->add(array(
            'name' => 'role_id',
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
    public function addFirstName()
    {
        $label = 'First Name';

        $this->add(array(
            'name' => 'first_name',
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
    public function addLastName()
    {
        $label = 'Last Name';

        $this->add(array(
            'name' => 'last_name',
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