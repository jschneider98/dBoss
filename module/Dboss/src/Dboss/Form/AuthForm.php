<?php
namespace Dboss\Form;

use Zend\Form\Form;

class AuthForm extends Form
{
    public function __construct()
    {
        parent::__construct('auth_form');
        $this->setAttribute('method', 'post');
    }

    /**
     * 
     **/
    public function setup()
    {
        $this->addUserName();
        $this->addPassword();
        $this->addSubmit();
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