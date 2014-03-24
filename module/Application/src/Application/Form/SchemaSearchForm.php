<?php
namespace Application\Form;

use Zend\Form\Form;

class SchemaSearchForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('schea_search_form');
        $this->setAttribute('method', 'get');

        $this->add(array(
            'name' => 'search',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Search',
            ),
        ));

        $this->add(array(
            'name' => 'search_submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Search',
                'id' => 'search_button',
            ),
        ));
    }
}