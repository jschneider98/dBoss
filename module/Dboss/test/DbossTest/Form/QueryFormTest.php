<?php

namespace DbossTest\Form;

use Dboss\Form\QueryForm;
use PHPUnit_Framework_TestCase;

class QueryFormTest extends PHPUnit_Framework_TestCase
{
    protected $form;

    /**
     * 
     */
    public function setUp()
    {
        $this->form = new QueryForm();
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

        $this->assertSame(
            6,
            $this->form->count(),
            "Incorrect number of form elements"
        );

        $this->assertSame(
            true,
            $this->form->has('sql'),
            'sql field missing from form'
        );

        $this->assertSame(
            true,
            $this->form->has('query_name'),
            'query_name field missing from form'
        );

        $this->assertSame(
            true,
            $this->form->has('multiple_queries'),
            'multiple_queries field missing from form'
        );

        $this->assertSame(
            true,
            $this->form->has('run_in_transaction'),
            'run_in_transaction field missing from form'
        );

        $this->assertSame(
            true,
            $this->form->has('run_submit'),
            'run_submit field missing from form'
        );

        $this->assertSame(
            true,
            $this->form->has('save_query'),
            'save_query field missing from form'
        );
    }
}