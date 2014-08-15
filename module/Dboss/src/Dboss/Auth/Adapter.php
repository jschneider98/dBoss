<?php

namespace Dboss\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Adapter\Exception\ExceptionInterface;

class Adapter implements AdapterInterface
{
    protected $user_service;
    protected $user_name;
    protected $password;

    /**
     * 
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        $user_service = null;
        $user_name = null;
        $password = null;

        extract($params, EXTR_IF_EXISTS);

        $this->user_service = $user_service;
        $this->user_name = $user_name;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        $auth_code = Result::SUCCESS;
        $messages = array();

        $user = $this->user_service->findByUserName($this->user_name);

        if (! $user) {
            
            $auth_code = Result::FAILURE_IDENTITY_NOT_FOUND;
            $messages[] = "Invalid user name.";
        } else if (! $user->checkPassword($this->password)) {
            
            $auth_code = Result::FAILURE_CREDENTIAL_INVALID;
            $messages[] = "Incorrect password.";
        }

        return new Result($auth_code, $user->user_id, $messages);
    }
}