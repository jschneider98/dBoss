<?php
/**
 * Default index controller. Default page with dBoss info and links.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Form\SchemaSearchForm;
use Dboss\Schema\Resource\ResourceFactory;
use Dboss\Schema\Resource\Null;

class SchemaController extends DbossActionController
{
    /**
     * 
     **/
    public function indexAction()
    {
        $search = "";
        $template = array();

        $resource_type = "table";

        $template = array(
            'results' => array(),
            'errors'  => array()
        );

        $form = new SchemaSearchForm();
        $template['form'] = $form;

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

                if ($schema_resource instanceof Null) {
                    $template['not_implemented'] = "This feature is either not supported by your database platform or it has not been implemented yet.";
                }

                $results = $schema_resource->getEncodedResourceList(array('search' => $search));
                
                $template['results'] = $results;
                $template['row_count'] = count($results);
            }
        }

        return $template;
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

        $template = array();

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
        
        $template['definition'] = $schema_resource->getResourceDefinition($params);
        $template['schema_name'] = $schema_name;
        $template['resource_name'] = $resource_name;
        $template['resource'] = $resource_value;
        $template['resource_type'] = $resource_type;
        //$template['database'] = $this->_helper->getHelper('Database')->getDatabase();

        return $template;
    }
}
