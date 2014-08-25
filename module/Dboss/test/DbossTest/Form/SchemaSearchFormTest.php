<?php

namespace DbossTest\Form;

use Dboss\Form\SchemaSearchForm;
use PHPUnit_Framework_TestCase;

class SchemaSearchFormTest extends PHPUnit_Framework_TestCase
{
    protected $form;

    /**
     * 
     */
    public function setUp()
    {
        $this->form = new SchemaSearchForm();
    }

    /**
     * 
     */
    public function testFormConstruct()
    {
        $this->assertSame(
            'get',
            $this->form->getAttribute('method'),
            'Default form method should be get'
        );

        $this->assertSame(
            2,
            $this->form->count(),
            "Incorrect number of form elements"
        );

        $this->assertSame(
            true,
            $this->form->has('search'),
            'search field missing from form'
        );

        $this->assertSame(
            true,
            $this->form->has('search_submit'),
            'search_submit field missing from form'
        );
    }
}