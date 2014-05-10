<?php
/**
 * Base controller.
 * @TODO: Eliminate this class for something better. Events?
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;

abstract class DbossActionController extends AbstractActionController
{
    public $require_login = true;
    public $require_connection = true;
    public $user;
    public $db;

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($event) use ($controller) {
            $request = $event->getRequest();
            $method  = $request->getMethod();
            $controller->user = $this->getUser();
            $controller->db = $this->getDb();
        }, 100);
    }

    /**
     * 
     **/
    protected function getUser()
    {
        if ($this->user) {
            return $this->user;
        }

        // @TODO: Replace with Zend\Authentication\AuthenticationService etc
        $user_service = $this->getServiceLocator()->get('Application\Service\UserService');
        $this->user = $user_service->findOneBy(array('user_id' => 1));

        return $this->user;
    }

    /**
     * 
     **/
    protected function getDb()
    {
        if ($this->db) {
            return $this->db;
        }

        $config = $this->getServiceLocator()->get('config');
        $this->db = new Adapter($config['temp_db']);

        return $this->db;
    }
}