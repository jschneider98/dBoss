<?php
/**
 * Default index controller. Default page with dBoss info and links.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends DbossActionController
{
    public $require_login = false;
    public $require_connection = false;

    public function indexAction()
    {
        return new ViewModel();
    }
}
