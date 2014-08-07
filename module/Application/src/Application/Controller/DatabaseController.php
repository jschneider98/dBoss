<?php
/**
 * Database controller. Search and select database connections
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Application\Form\SchemaSearchForm;
use Dboss\Schema\Resource\ResourceFactory;
use Dboss\Schema\Resource\Null;

class DatabaseController extends DbossActionController
{
    public $require_login = true;
    public $require_connection = false;

    /**
     * 
     **/
    public function indexAction()
    {
        $template = array(
            'connection_info' => $this->user->getConnectionInfo()
        );

        if ($this->connection_string) {
            $template['connection_string'] = $this->connection_string;
        }

        return $template;
    }
}