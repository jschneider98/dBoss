<?php
namespace Application\Form;

use Zend\Form\Form;

class QueryForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('query_form');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'sql',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'SQL',
            ),
        ));

        $this->add(array(
            'name' => 'run_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Run',
                'id'    => 'run_button',
            ),
        ));
    }
}