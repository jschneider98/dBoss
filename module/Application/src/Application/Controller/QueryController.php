<?php
/**
 * Query controller.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Form\QueryForm;
use Dboss\Schema\Resource\ResourceFactory;
use Dboss\QueryRunner;

class QueryController extends AbstractActionController
{
    public function indexAction()
    {
        $query_type = null;

        $params = $this->params()->fromRoute();

        extract($params, EXTR_IF_EXISTS);

        if ($query_type) {
            $sql = $this->getSql($params);
        }

        $template = array(
            'results' => array(),
            'errors'  => array()
        );

        $form = new QueryForm();
        $template['form'] = $form;

        if ($sql) {
            $form->setData(array('sql' => $sql));
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                list($success, $results) = $this->runSql($form->get('sql')->getValue());

                if ($success) {
                    $template['results'] = $results;
                } else {
                    $template['errors'] = $results;
                }
            }
        }

        return $template;
    }


    /**
     * Generate various SQL queries (SELECT, INSERT, UPDATE, etc)
     **/
    protected function getSql(array $params = array())
    {
        $query_type = null;
        $schema_name = null;
        $resource_name = null;
        $with_field_names = null;

        extract($params, EXTR_IF_EXISTS);

        // @TEMP
        $config = $this->getServiceLocator()->get('config');
        $db = new Adapter($config['temp_db']);

        $params = array(
            'resource_type' => 'table',
            'db'            => $db
        );

        $resource_factory = new ResourceFactory($params);
        $schema_resource = $resource_factory->getResource();

        $params = array(
            'schema_name'      => $schema_name,
            'resource_name'    => $resource_name,
            'with_field_names' => $with_field_names
        );

        // Dynamic function call
        $func = "get" . $query_type . "Sql";
        return $schema_resource->$func($params);
    }

    /**
     * @TEMP: Just testing
     */
    protected function runSql($sql = null)
    {
        // @TEMP
        $config = $this->getServiceLocator()->get('config');
        $db = new Adapter($config['temp_db']);

        $params = array(
            //'user_id'            => $this->_getIdentity()->user_id,
            'sql'                => $sql,
            //'query_name'         => $post_vals['query_name'],
            //'run_in_transaction' => $post_vals['run_in_transaction'],
            //'multiple_queries'   => $post_vals['multiple_queries'],
            'db'                 => $db,
            //'sys_db'             => $this->getFrontController()->getParam('bootstrap')->getResource('db')
        );

        $query_runner = new QueryRunner($params);

        $results = $query_runner->execSql($sql);

        if ($results) {
            return array(true, $results);
        } else {
            return array(false, $query_runner->getErrors());
        }
    }
}
