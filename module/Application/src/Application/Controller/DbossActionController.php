<?php
/**
 * Base controller.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Model\ViewModel;

abstract class DbossActionController extends AbstractActionController
{
    public $require_login = true;
    public $require_connection = true;
    public $user;
    public $db;
    public $connection_string = null;
    public $host_name;

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($event) use ($controller) {
            // $request = $event->getRequest();
            // $method  = $request->getMethod();
            
            $controller->user = $this->getUser();
            
            $controller->connection_string = $this->getConnectionString();
            $controller->layout()->connection_string = $this->connection_string;
            
            $controller->db = $this->getDb();
            $controller->layout()->host_name = $this->host_name;
            
            if ($controller->require_connection && ! $this->db) {
                $this->flashMessenger()
                        ->setNamespace('error')
                        ->addMessage("This action requires a database connection and you haven't selected one yet. Please select one below.");
                return $this->redirect()->toRoute('database');
            }
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

        $this->getUser();
        $this->getConnectionString();

        list($connection_id, $database_name) = explode("-", $this->connection_string);

        if (! $connection_id || ! $database_name) {
            return null;
        }

        $connection_service = $this->getServiceLocator()->get('Application\Service\ConnectionService');

        $connection = $connection_service->findOneBy(
            array(
                'user_id'       => $this->user->user_id,
                'connection_id' => $connection_id,
            )
        );

        if (! $connection) {
            return null;
        }

        if ($connection->is_server_connection) {
            $connection->database_name = $database_name;
        }

        $this->db = $connection->connect();
        $this->host_name = $connection->host;

        // $config = $this->getServiceLocator()->get('config');
        // $this->db = new Adapter($config['temp_db']);

        return $this->db;
    }

    /**
     * 
     **/
    protected function getConnectionString()
    {
        if ($this->connection_string) {
            return $this->connection_string;
        }

        $params = $this->params()->fromRoute();
        $this->connection_string = (isset($params['connection_string'])) ? $params['connection_string'] : null;

        return $this->connection_string;
    }
}