<?php
/**
 * Default index controller. Default page with dBoss info and links.
 */

namespace Dboss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends DbossActionController
{
    public $require_login = false;
    public $require_connection = false;

    public function indexAction()
    {
        return $this->view_model;
    }
}
