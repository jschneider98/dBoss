<?php
/**
 * Database controller. Search and select database connections
 */

namespace Dboss\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
//use Zend\View\Model\ViewModel;
//use Zend\Db\Adapter\Adapter;
//use Dboss\Form\SchemaSearchForm;
//use Dboss\Schema\Resource\ResourceFactory;
//use Dboss\Schema\Resource\NullResource;

class DatabaseController extends DbossActionController
{
    public $require_login = true;
    public $require_connection = false;

    /**
     *
     **/
    public function indexAction()
    {
        $this->view_model->setVariable(
            'connection_info',
            $this->user->getConnectionInfo()
        );

        return $this->view_model;
    }
}
