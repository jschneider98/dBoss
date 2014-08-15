<?php
/**
 * User Administration Controller
 */

namespace Dboss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Dboss\Form\UserForm;
use Dboss\Form\ConnectionForm;
use Dboss\Entity\User;

class AdminController extends DbossActionController
{
    public $require_login = true;
    public $require_connection = false;

    protected $user_service;
    protected $connection_service;
    protected $role_service;

    /**
     * 
     **/
    public function indexAction()
    {
        if ($this->user->isLimited()) {
            $this->flashMessenger()
                    ->setNamespace('error')
                    ->addMessage("You do not have access to administration");

            $params = array();

            if ($this->connection_string) {
                $params['connection_string'] = $this->connection_string;
            }

            return $this->redirect()->toRoute('home', $params);
        }

        $user_service = $this->getUserService();

        if ($this->user->isaBoss()) {
            $this->view_model->setVariable(
                'users',
                $user_service->findActiveUsers()
            );
        } else {
            $this->view_model->setVariable(
                'users',
                array(
                    $user_service->findOneBy(array('user_id' => $this->user->user_id))
                )
            );
        }

        return $this->view_model;
    }

    /**
     * 
     **/
    public function editAction()
    {
        $user_id = (int) $this->params()->fromRoute(
            'user_id',
            $this->params()->fromPost('user_id', 0)
        );

        $user = $this->getUserService()->findOrCreate($user_id);
        $user->password = null;

        $this->view_model->setVariable(
            'user',
            $user
        );

        $form = new UserForm();
        $form->setup();
        $form->bind($user);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $form->getData();
                $role = $this->getRoleService()->find($user->role_id);
                $user->role = $role;

                $this->getUserService()->save($user);

                $this->flashMessenger()->setNamespace('success')->addMessage("Data saved successfully");

                $params = array();

                if ($this->connection_string) {
                    $params['connection_string'] = $this->connection_string;
                }

                return $this->redirect()->toRoute('admin', $params);
            }
        }

        $this->view_model->setVariable('form', $form);
        $this->view_model->setVariable('user_id', $user_id);

        return $this->view_model;
    }

    /**
     * 
     **/
    public function connectionListAction()
    {
        $user_id = (int) $this->params()->fromRoute('user_id', 0);

        if (! $this->user->canEditUser($user_id)) {
            $this->flashMessenger()
                    ->setNamespace('error')
                    ->addMessage("You do not have access to this user");
            return array();
        }

        $user = $this->getUserService()->find($user_id);

        if (! $user) {
            $this->flashMessenger()
                    ->setNamespace('error')
                    ->addMessage("User not found");
            return array();
        }

        $this->view_model->setVariable(
            'user',
            $user
        );

        return $this->view_model;
    }

    /**
     * 
     **/
    public function connectionEditAction()
    {
        $user_id = (int) $this->params()->fromRoute(
            'user_id',
            $this->params()->fromPost('user_id', 0)
        );

        $connection_id = (int) $this->params()->fromRoute(
            'connection_id',
            $this->params()->fromPost('connection_id', 0)
        );

        $user = $this->getUserService()->findOrCreate($user_id);
        $connection = $this->getConnectionService()->findOrCreate($connection_id);

        if (! $connection->user_id) {
            $connection->user_id = $user->user_id;
        }

        $this->view_model->setVariables(
            array(
                'user'        => $user,
                'connnection' => $connnection
            )
        );

        $form = new ConnectionForm();
        $form->setup();
        $form->bind($connection);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($connection->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $connection = $form->getData();
                $user = $this->getUserService()->find($connection->user_id);
                $connection->user = $user;

                $this->getConnectionService()->save($connection);

                $this->flashMessenger()->setNamespace('success')->addMessage("Data saved successfully");
                
                $params = array('user_id' => $connection->user_id);

                if ($this->connection_string) {
                    $params['connection_string'] = $this->connection_string;
                }

                return $this->redirect()->toRoute(
                    'admin',
                    $params
                );
            }
        }

        $this->view_model->setVariable('form', $form);
        $this->view_model->setVariable('user_id', $user_id);

        return $this->view_model;
    }

    /**
     * 
     **/
    protected function getUserService()
    {
        if (! $this->user_service) {
            $this->user_service = $this->getServiceLocator()->get('Dboss\Service\UserService');
        }
        return $this->user_service;
    }

    /**
     * 
     **/
    protected function getRoleService()
    {
        if (! $this->role_service) {
            $this->role_service = $this->getServiceLocator()->get('Dboss\Service\RoleService');
        }
        return $this->role_service;
    }

    /**
     * 
     **/
    protected function getConnectionService()
    {
        if (! $this->connection_service) {
            $this->connection_service = $this->getServiceLocator()->get('Dboss\Service\ConnectionService');
        }
        return $this->connection_service;
    }
}
