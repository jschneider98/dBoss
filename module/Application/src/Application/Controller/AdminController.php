<?php
/**
 * User Administration Controller
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends DbossActionController
{
    public $require_login = true;
    public $require_connection = false;

    public function indexAction()
    {
        return new ViewModel();
    }
}
