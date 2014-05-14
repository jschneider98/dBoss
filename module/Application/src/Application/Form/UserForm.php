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
        $this->add(array(
            'name' => 'user_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'User Name',
            ),
        ));
    }

    /**
     * 
     **/
    public function addFirstName()
    {
        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));
    }

    /**
     * 
     **/
    public function addLastName()
    {
        $this->add(array(
            'name' => 'last_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));
    }

    /**
     * 
     **/
    public function addPassword()
    {
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
    }

    /**
     * 
     **/
    public function addVerifyPassword()
    {
        $this->add(array(
            'name' => 'verify_password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Verify Password',
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
            ),
        ));
    }
}