<?php
namespace DbossTest\Auth;

use Dboss\Auth\Adapter;
use Zend\Authentication\Result;
use PHPUnit_Framework_TestCase;

class AdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testAuthenticateIdentityNotFound()
    {
        $user_service_mock = $this->getMock(
            '\Dboss\Service\UserService',
            array(),
            array(),
            '',
            false
        );

        $user_service_mock->expects($this->any())
            ->method('findByUserName')
            ->will($this->returnValue(null));

        $params = array(
            'user_service' => $user_service_mock,
            'user_name'    => 'unit_test_no_user',
            'password'     => 'password',
        );

        $adapter = new Adapter($params);
        $result = $adapter->authenticate();


        $this->assertSame(
            Result::FAILURE_IDENTITY_NOT_FOUND,
            $result->getCode(),
            "Result code should match FAILURE_IDENTITY_NOT_FOUND"
        );
    }

    /**
     * 
     */
    public function testAuthenticateCredentialInvalid()
    {
        $user_mock = $this->getMock('\Dboss\Entity\User');
        $user_mock->expects($this->any())
            ->method('checkPassword')
            ->will($this->returnValue(false));

        $user_service_mock = $this->getMock(
            '\Dboss\Service\UserService',
            array(),
            array(),
            '',
            false
        );

        $user_service_mock->expects($this->any())
            ->method('findByUserName')
            ->will($this->returnValue($user_mock));

        $params = array(
            'user_service' => $user_service_mock,
            'user_name'    => 'unit_test_no_user',
            'password'     => 'password',
        );

        $adapter = new Adapter($params);
        $result = $adapter->authenticate();


        $this->assertSame(
            Result::FAILURE_CREDENTIAL_INVALID,
            $result->getCode(),
            "Result code should match FAILURE_CREDENTIAL_INVALID"
        );
    }

    /**
     * 
     */
    public function testAuthenticateSuccess()
    {
        $user_mock = $this->getMock('\Dboss\Entity\User');
        $user_mock->expects($this->any())
            ->method('checkPassword')
            ->will($this->returnValue(true));

        $user_service_mock = $this->getMock(
            '\Dboss\Service\UserService',
            array(),
            array(),
            '',
            false
        );

        $user_service_mock->expects($this->any())
            ->method('findByUserName')
            ->will($this->returnValue($user_mock));

        $params = array(
            'user_service' => $user_service_mock,
            'user_name'    => 'unit_test_no_user',
            'password'     => 'password',
        );

        $adapter = new Adapter($params);
        $result = $adapter->authenticate();


        $this->assertSame(
            Result::SUCCESS,
            $result->getCode(),
            "Result code should match SUCCESS"
        );
    }
}