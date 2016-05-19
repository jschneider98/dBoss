<?php
/**
 * Database controller. Search and select database connections
 */

namespace Dboss\Controller;

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
