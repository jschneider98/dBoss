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

class SchemaController extends AbstractActionController
{
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
                $config = $this->getServiceLocator()->get('config');
                $db = new Adapter($config['db']);

                $params = array(
                    'resource_type' => $resource_type,
                    'db'            => $db
                );

                $schema_resource = ResourceFactory::getResource($params);
                $results = $schema_resource->getEncodedResourceList(array('search' => $search));
                
                $template['results'] = $results;
                $template['row_count'] = count($results);
            }
        }

        /*
        $params = array(
            'resource_type' => $resource_type,
            'db'            => $this->_helper->getHelper('Database')->getConnection()
        );

        $schema_resource = SQLBoss_Schema_Resource_Factory::getResource($params);

        if ($schema_resource instanceof SQLBoss_Schema_Resource_Null) {

            $this->view->not_implemented = "This feature is either not supported by your database platform or it has not been implemented yet.";
        }

        $this->view->results = $schema_resource->getEncodedResourceList(array('search' => $search));
        $this->view->row_count = count($this->view->results);

        $this->view->form = $form;
        $this->view->database = $this->_helper->getHelper('Database')->getDatabase();
        */

        return $template;
    }
}
