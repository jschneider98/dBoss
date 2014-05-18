<?php
/**
 * User Administration Controller
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\UserForm;
use Application\Form\ConnectionForm;
use Application\Entity\User;

class AdminController extends DbossActionController
{
    public $require_login = true;
    public $require_connection = false;

    protected $user_service;
    protected $connection_service;

    /**
     * 
     **/
    public function indexAction()
    {
        $template = array();

        if ($this->user->isLimited()) {
            // no access, redirect
        }

        $user_service = $this->getUserService();

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
        $user_id = (int) $this->params()->fromRoute('user_id', 0);
        $user = $this->getUserService()->findOrCreate($user_id);
        $user->password = null;

        $template = array('user' => $user);
        $form = new UserForm();
        $form->setup();
        $form->bind($user);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getUserService()->save($form->getData());

                $this->flashMessenger()->setNamespace('success')->addMessage("Data saved successfully");
                return $this->redirect()->toRoute('admin');
            }
        }

        $template['form'] = $form;
        $template['user_id'] = $user_id;

        return $template;
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

        $template = array('user' => $user);

        return $template;
    }

    /**
     * 
     **/
    public function connectionEditAction()
    {
        $user_id = (int) $this->params()->fromRoute('user_id', 0);
        $connection_id = (int) $this->params()->fromRoute('connection_id', 0);
        $user = $this->getUserService()->findOrCreate($user_id);
        $connection = $this->getConnectionService()->findOrCreate($connection_id);

        $template = array(
            'user'        => $user,
            'connnection' => $connnection
        );

        $form = new ConnectionForm();
        $form->setup();
        //$form->bind($connection);

        $request = $this->getRequest();

        if ($request->isPost()) {
            //$form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getConnectionService()->save($form->getData());

                $this->flashMessenger()->setNamespace('success')->addMessage("Data saved successfully");
                return $this->redirect()->toRoute('admin');
            }
        }

        $template['form'] = $form;
        $template['user_id'] = $user_id;

        return $template;
    }

    /**
     * 
     **/
    protected function getUserService()
    {
        if (! $this->user_service) {
            $this->user_service = $this->getServiceLocator()->get('Application\Service\UserService');
        }
        return $this->user_service;
    }

    /**
     * 
     **/
    protected function getConnectionService()
    {
        if (! $this->connection_service) {
            $this->connection_service = $this->getServiceLocator()->get('Application\Service\ConnectionService');
        }
        return $this->connection_service;
    }
}
