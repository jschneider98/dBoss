<?php
/**
 * Query controller.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Form\QueryForm;
use Dboss\QueryRunner;

class QueryController extends AbstractActionController
{
    public function indexAction()
    {
        $template = array(
            'results' => array(),
            'errors'  => array()
        );

        $form = new QueryForm();
        $template['form'] = $form;

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
     * @TEMP: Just testing
     */
    protected function runSql($sql = null)
    {
        $config = $this->getServiceLocator()->get('config');
        $db = new Adapter($config['db']);

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
