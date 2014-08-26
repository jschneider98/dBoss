<?php
/**
 * Base controller.
 */

namespace Dboss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as ZendSession;

abstract class DbossActionController extends AbstractActionController
{
    public $require_login = true;
    public $require_connection = true;

    public $view_model = null;
    public $user;
    public $db;
    public $connection_string = null;
    public $host_name;

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($event) use ($controller) {
            $controller->user = $controller->getUser();
            
            $controller->connection_string = $controller->getConnectionString();
            $controller->layout()->connection_string = $controller->connection_string;
            $controller->layout()->signed_in_user = $controller->user;
            
            $controller->db = $controller->getDb();
            $controller->layout()->host_name = $controller->host_name;
            
            if ($controller->require_connection && ! $controller->db) {
                $controller->flashMessenger()
                        ->setNamespace('error')
                        ->addMessage("This action requires a database connection and you haven't selected one yet. Please select one below.");
                return $controller->redirect()->toRoute('database');
            }

            if ($controller->require_login && ! $controller->user) {
                $controller->flashMessenger()
                        ->setNamespace('error')
                        ->addMessage("This action requires you to be logged in. Please enter your user name and password below.");
                return $controller->redirect()->toRoute('auth');
            }

            $controller->view_model = new ViewModel(
                array(
                    'connection_string' => $controller->connection_string,
                )
            );
        }, 100);
    }

    /**
     * 
     **/
    public function getUser()
    {
        if ($this->user) {
            return $this->user;
        }

        $auth_service = new AuthenticationService();
        $auth_service->setStorage(new ZendSession('dBoss_Auth'));

        if ($auth_service->hasIdentity()) {
            $user_id = $auth_service->getIdentity();
            $this->user = $this->getServiceLocator()->get('Dboss\Service\UserService')->find($user_id);
        }

        return $this->user;
    }

    /**
     * 
     **/
    public function getDb()
    {
        if ($this->db) {
            return $this->db;
        }

        $this->getUser();

        if (! $this->user) {
            return;
        }

        $connection_id = null;
        $database_name = null;

        $this->getConnectionString();

        if ($this->connection_string) {
            list($connection_id, $database_name) = explode("-", $this->connection_string);
        }

        if (! $connection_id || ! $database_name) {
            return null;
        }

        $connection_service = $this->getServiceLocator()->get('Dboss\Service\ConnectionService');

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
            // Copy the connection so we don't accidently make changes to the orginal
            $data = $connection->getArrayCopy();
            $data['database_name'] = $database_name;
            unset($data['connection_id']);

            $connection = $connection_service->create();
            $connection->exchangeArray($data);
        }

        $this->db = $connection->connect();
        $this->host_name = $connection->host;

        return $this->db;
    }

    /**
     * 
     **/
    public function getConnectionString()
    {
        if ($this->connection_string) {
            return $this->connection_string;
        }

        $params = $this->params()->fromRoute();
        $this->connection_string = (isset($params['connection_string'])) ? $params['connection_string'] : null;

        return $this->connection_string;
    }
}