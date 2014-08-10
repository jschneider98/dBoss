<?php
/**
 * Query controller.
 */

namespace Application\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Form\QueryForm;
use Dboss\Schema\Resource\ResourceFactory;
use Dboss\QueryRunner;

class QueryController extends DbossActionController
{
    protected $query_service;

    /**
     * 
     **/
    public function indexAction()
    {
        $query_type = null;
        $query_id = null;

        $params = $this->params()->fromRoute();

        extract($params, EXTR_IF_EXISTS);

        $sql = null;

        if ($query_type) {
            $sql = $this->getSql($params);
        }

        if ($query_id && is_numeric($query_id)) {

            if ($this->user->isMyQuery($query_id)) {
                $this->getQueryService();
                $query = $this->query_service->find($query_id);

                if ($query) {
                    $sql = $query->query;
                } else {
                    $this->flashMessenger()
                            ->setNamespace('error')
                            ->addMessage("Invalid query");
                }
            } else {
                $this->flashMessenger()
                        ->setNamespace('error')
                        ->addMessage("You do not have access to this query");
            }
        }

        $template = array(
            'connection_string' => $this->connection_string,
            'results'           => array(),
            'errors'            => array()
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

                $params = array(
                    'sql'                => $form->get('sql')->getValue(),
                    'query_name'         => $form->get('query_name')->getValue(),
                    'multiple_queries'   => $form->get('multiple_queries')->getValue(),
                    'run_in_transaction' => $form->get('run_in_transaction')->getValue(),
                );

                list($success, $results) = $this->runSql($params);

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
     * 
     **/
    public function historyAction()
    {
        $criteria = array(
            'user_id'       => $this->user->user_id,
            'query_name'    => null,
            'deletion_date' => null
        );

        $order_by = array("modification_date" => "DESC");

        $limit = null;
        //$limit = 100;

        $template = array(
            'connection_string' => $this->connection_string,
            'queries' => $this->getQueryService()->findBy($criteria, $order_by, $limit),
        );

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

        $params = array(
            'resource_type' => 'table',
            'db'            => $this->db
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
     * 
     **/
    protected function runSql(array $params = array())
    {
        $sql = null;
        $query_name = null;
        $run_in_transaction = false;
        $multiple_queries = true;

        extract($params, EXTR_IF_EXISTS);

        $query_params = array(
            'user'               => $this->user,
            'sql'                => $sql,
            'query_name'         => $query_name,
            'run_in_transaction' => $run_in_transaction,
            'multiple_queries'   => $multiple_queries,
            'db'                 => $this->db,
            'query_service'      => $this->getQueryService(),
        );

        $query_runner = new QueryRunner($query_params);

        $results = $query_runner->execSql($sql);

        if ($results) {
            return array(true, $results);
        } else {
            return array(false, $query_runner->getErrors());
        }
    }

    /**
     * 
     **/
    protected function getQueryService()
    {
        if (! $this->query_service) {
            $this->query_service = $this->getServiceLocator()->get('Application\Service\QueryService');
        }
        return $this->query_service;
    }
}
