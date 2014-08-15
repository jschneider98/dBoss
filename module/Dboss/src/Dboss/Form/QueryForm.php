<?php
namespace Dboss\Form;

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
                'label' => 'Query',
            ),
        ));

        $this->add(array(
            'name' => 'query_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Query Name',
            ),
        ));

        $this->add(array(
            'type'  => 'Zend\Form\Element\Select',
            'name'  => 'multiple_queries',
            'attributes' => array(
                'value' => '1',
                'type'  => 'select',
            ),
            'options' => array(
                'label' => 'Multiple queries?',
                'value_options' => array(
                    '1' => 'Yes',
                    '0' => 'No'
                )
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'run_in_transaction',
            'attributes' => array(
                'value' => '1',
                'type'  => 'select',
            ),
            'options' => array(
                'label' => 'Run in transaction?',
                'value_options' => array(
                    '1' => 'Yes',
                    '0' => 'No'
                )
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

        $this->add(array(
            'name' => 'save_query',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save Query',
                'id'    => 'save_query',
            ),
        ));
    }
}