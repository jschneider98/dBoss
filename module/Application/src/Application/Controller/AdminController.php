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

    /**
     * 
     **/
    public function indexAction()
    {
        $template = array();

        if ($this->user->isLimited()) {
            // no access, redirect
        }

        $user_service = $this->getServiceLocator()->get('Application\Service\UserService');

        if ($this->user->isaBoss()) {
            $template['users'] = $user_service->findActiveUsers();
        } else {
            $template['users'] = $user_service->findInactiveUsers();
        }

        return $template;
    }

    /**
     * 
     **/
    public function editAction()
    {
        return array();
    }
}
