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

class SchemaController extends AbstractActionController
{
    /**
     * 
     **/
    public function indexAction()
    {
        $search = "";
        $template = array();

        $resource_type = "everything";

        $template = array(
            'results' => array(),
            'errors'  => array()
        );

        $form = new SchemaSearchForm();
        $template['form'] = $form;

        $request = $this->getRequest();

        if ($request->isGet()) {
            $form->setData($request->getPost());

            $search = $request->getQuery('search');

            if ($search !== null) {
                // @TEMP
                $config = $this->getServiceLocator()->get('config');
                $db = new Adapter($config['db']);

                $params = array(
                    'resource_type' => $resource_type,
                    'db'            => $db
                );

                $schema_resource = ResourceFactory::getResource($params);

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
        $resource_name = null;
        $resource_arguments = null;
        $resource_value = null;

        $params = $this->params()->fromRoute();

        extract($params, EXTR_IF_EXISTS);

        $template = array();

        // @TEMP
        $config = $this->getServiceLocator()->get('config');
        $db = new Adapter($config['db']);

        $params = array(
            'resource_type' => $resource_type,
            'db'            => $db
        );

        $schema_resource = ResourceFactory::getResource($params);

        $params = array(
            'schema_name'        => $schema_name,
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
