<?php
/**
 * Authentication controller
 */

namespace Dboss\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as ZendSession;

use Dboss\Form\AuthForm;
use Dboss\Form\AuthFilter;

class AuthController extends DbossActionController
{
    public $require_login = false;
    public $require_connection = false;

    public function indexAction()
    {
        $auth_service = new AuthenticationService();
        $auth_service->setStorage(new ZendSession('dBoss_Auth'));

        if ($auth_service->hasIdentity()) {
            $this->flashMessenger()
                    ->setNamespace('error')
                    ->addMessage("You're already logged in.");

            $params = array();

            if ($this->connection_string) {
                $params['connection_string'] = $this->connection_string;
            }

            return $this->redirect()->toRoute('home', $params);
        }

        $form = new AuthForm();
        $form->setup();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter(new AuthFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $form_data = $form->getData();

                $params = array(
                    'user_service' => $this->getServiceLocator()->get('Dboss\Service\UserService'),
                    'user_name'    => $form_data['user_name'],
                    'password'     => $form_data['password'],
                );

                $auth_adapter = new \Dboss\Auth\Adapter($params);
                $result = $auth_service->authenticate($auth_adapter);

                if (! $result->isValid()) {

                    $error_message = implode("<br>", $result->getMessages());

                    $this->flashMessenger()
                            ->setNamespace('error')
                            ->addMessage($error_message);

                    return $this->redirect()->toRoute('auth', $params);
                } else {

                    $this->flashMessenger()
                        ->setNamespace('success')
                        ->addMessage("You are now signed in. Welcome to dBoss!");

                    $params = array();

                    if ($this->connection_string) {
                        $params['connection_string'] = $this->connection_string;
                    }

                    return $this->redirect()->toRoute('home', $params);
                }
            }
        }

        $this->view_model->setVariable('form', $form);

        return $this->view_model;
    }

    /**
     * 
     */
    public function signoutAction()
    {
        $auth_service = new AuthenticationService();
        $auth_service->setStorage(new ZendSession('dBoss_Auth'));

        if ($auth_service->hasIdentity()) {
            $auth_service->clearIdentity();

            $this->flashMessenger()
                ->setNamespace('success')
                ->addMessage("You have been signed out successfully. Have a good one.");
        }

        return $this->redirect()->toRoute('home');
    }
}