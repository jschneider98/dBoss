<?php
/**
 * Schema controller. Search and display schema info
 */

namespace Dboss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Dboss\Form\SchemaSearchForm;
use Dboss\Schema\Resource\ResourceFactory;
use Dboss\Schema\Resource\NullResource;

class SchemaController extends DbossActionController
{
    /**
     *
     **/
    public function indexAction()
    {
        $search = "";
        $resource_type = "table";

        $this->view_model->setVariables(
            array(
                'results' => array(),
                'errors'  => array()
            )
        );

        $form = new SchemaSearchForm();
        $this->view_model->setVariable('form', $form);

        $request = $this->getRequest();

        if ($request->isGet()) {
            $get_data = $request->getQuery();
            $form->setData($get_data);

            $search = $get_data['search'];

            if ($search !== null) {
                // @TEMP: Move to obj?
                // Parse out the resource_type from search string
                $pos = strpos($search, ":");

                if ($pos !== false) {
                    $search_parts = explode(":", $search);
                    $resource_type = trim($search_parts[0]);
                    $search = trim($search_parts[1]);
                }

                $params = array(
                    'resource_type' => $resource_type,
                    'db'            => $this->db
                );

                $resource_factory = new ResourceFactory($params);
                $schema_resource = $resource_factory->getResource();

                if ($schema_resource instanceof NullResource) {
                    $this->view_model->setVariable(
                        'not_implemented',
                        "This feature is either not supported by your database platform or it has not been implemented yet."
                    );
                }

                $results = $schema_resource->getEncodedResourceList(array('search' => $search));

                $this->view_model->setVariable('results', $results);
                $this->view_model->setVariable('row_count', count($results));
            }
        }

        return $this->view_model;
    }

    /**
     *
     */
    public function definitionAction()
    {
        $resource_type = null;
        $schema_name = null;
        $table_name = null;
        $resource_name = null;
        $resource_arguments = null;
        $resource_value = null;

        $params = $this->params()->fromRoute();

        extract($params, EXTR_IF_EXISTS);

        $params = array(
            'resource_type' => $resource_type,
            'db'            => $this->db
        );

        $resource_factory = new ResourceFactory($params);
        $schema_resource = $resource_factory->getResource();

        $params = array(
            'schema_name'        => $schema_name,
            'table_name'         => $table_name,
            'resource_name'      => $resource_name,
            'resource_arguments' => $resource_arguments
        );

        $this->view_model->setVariable('definition', $schema_resource->getResourceDefinition($params));
        $this->view_model->setVariable('schema_name', $schema_name);
        $this->view_model->setVariable('resource_name', $resource_name);
        $this->view_model->setVariable('resource', $resource_value);
        $this->view_model->setVariable('resource_type', $resource_type);

        return $this->view_model;
    }
}
