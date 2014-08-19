<?php
namespace DbossTest\Entity;

use Dboss\Entity\User;
use Dboss\Entity\Role;
use Dboss\Xtea;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    protected $user;

    public function setUp()
    {
        $this->user = new User();
        $this->user->security = array(
            'salt_key'        => "salt_key",
            'iteration_count' => 8,
            'portable_hashes' => 0,
        );
    }

    /**
     * 
     */
    public function testUserInitialState()
    {
        $this->assertNull(
            $this->user->user_id,
            "user_id should initially be null"
        );

        $this->assertNull(
            $this->user->role_id,
            "role_id should initially be null"
        );

        $this->assertNull(
            $this->user->user_name,
            "user_name should initially be null"
        );

        $this->assertNull(
            $this->user->first_name,
            "first_name should initially be null"
        );

        $this->assertNull(
            $this->user->last_name,
            "last_name should initially be null"
        );

        $this->assertNull(
            $this->user->password,
            "password should initially be null"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $data  = array(
            "user_id"    => "user_id",
            "role_id"    => "role_id",
            "user_name"  => "user_name",
            "first_name" => "first_name",
            "last_name"  => "last_name",
            "password"   => "password",
        );

        $this->user->exchangeArray($data);

        $this->assertSame(
            $data['user_id'],
            $this->user->user_id,
            "user_id was not set correctly"
        );

        $this->assertSame(
            $data['role_id'],
            $this->user->role_id,
            "role_id was not set correctly"
        );

        $this->assertSame(
            $data['user_name'],
            $this->user->user_name,
            "user_name was not set correctly"
        );

        $this->assertSame(
            $data['first_name'],
            $this->user->first_name,
            "first_name was not set correctly"
        );

        $this->assertSame(
            $data['last_name'],
            $this->user->last_name,
            "last_name was not set correctly"
        );

        $this->assertSame(
            $this->user->checkPassword($data['password']),
            true,
            "password was not set correctly"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $data  = array(
            "user_id"    => "user_id",
            "role_id"    => "role_id",
            "user_name"  => "user_name",
            "first_name" => "first_name",
            "last_name"  => "last_name",
            "password"   => "password",
        );

        $this->user->exchangeArray($data);
        $this->user->exchangeArray(array());

        $this->assertNull(
            $this->user->user_id,
            "user_id should initially be null"
        );

        $this->assertNull(
            $this->user->role_id,
            "role_id should initially be null"
        );

        $this->assertNull(
            $this->user->user_name,
            "user_name should initially be null"
        );

        $this->assertNull(
            $this->user->first_name,
            "first_name should initially be null"
        );

        $this->assertNull(
            $this->user->last_name,
            "last_name should initially be null"
        );

        $this->assertNull(
            $this->user->password,
            "password should initially be null"
        );
    }

    /**
     * 
     */
    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $original_data  = array(
            "user_id"    => "user_id",
            "role_id"    => "role_id",
            "user_name"  => "user_name",
            "first_name" => "first_name",
            "last_name"  => "last_name",
            "password"   => "password",
        );

        $this->user->exchangeArray($original_data);
        $data = $this->user->getArrayCopy();

        $this->assertSame(
            $data['user_id'],
            $this->user->user_id,
            "user_id was not set correctly"
        );

        $this->assertSame(
            $data['role_id'],
            $this->user->role_id,
            "role_id was not set correctly"
        );

        $this->assertSame(
            $data['user_name'],
            $this->user->user_name,
            "user_name was not set correctly"
        );

        $this->assertSame(
            $data['first_name'],
            $this->user->first_name,
            "first_name was not set correctly"
        );

        $this->assertSame(
            $data['last_name'],
            $this->user->last_name,
            "last_name was not set correctly"
        );

        $this->assertSame(
            $data['password'],
            $this->user->password,
            "password was not set correctly"
        );
    }

    /**
     * 
     */
    public function testSetInputFilterFails()
    {
        $input_filter = $this->user->getInputFilter();
        
        $this->setExpectedException("\Exception");
        $this->user->setInputFilter($input_filter);
    }

    /**
     * 
     */
    public function testInputFiltersAreSetCorrectly()
    {
        $input_filter = $this->user->getInputFilter();

        $this->assertSame(6, $input_filter->count());

        $this->assertTrue($input_filter->has('user_id'));
        $this->assertTrue($input_filter->has('role_id'));
        $this->assertTrue($input_filter->has('user_name'));
        $this->assertTrue($input_filter->has('first_name'));
        $this->assertTrue($input_filter->has('last_name'));
        $this->assertTrue($input_filter->has('password'));
    }

    /**
     * 
     */
    public function testCheckPassword()
    {
        $password = "test";

        $this->assertSame(
            false,
            $this->user->checkPassword(null),
            "'null' passed to checkPassword did not return false"
        );

        $this->user->password = $password;

        $this->assertSame(
            true,
            $this->user->checkPassword($password),
            "Password did not match when it should have"
        );
    }

    /**
     * 
     */
    public function testGetRawUserName()
    {
        $user_name = "unit_test_user";
        $this->user->user_name = $user_name;

        $xtea = new Xtea($this->user->security['salt_key']);
        $encrypted_user_name = $xtea->encrypt($user_name);

        $this->assertSame(
            $encrypted_user_name,
            $this->user->getRawUserName(),
            "getRawUserName value did not match"
        );
    }

    /**
     * 
     */
    public function testSetSecurity()
    {
        $security = array(
            'salt_key'        => "salt_key",
            'iteration_count' => 8,
            'portable_hashes' => 0,
        );

        $this->user->setSecurity($security);

        $this->assertSame(
            $security,
            $this->user->security,
            "Security does not match"
        );

        $this->setExpectedException("\Zend\Stdlib\Exception\BadMethodCallException");
        $this->user->setSecurity(array());
    }

    /**
     * 
     */
    public function testIsaBoss()
    {
        $role = new Role();

        $this->user->role = null;

        $this->assertSame(
            false,
            $this->user->isaBoss(),
            "'null' role should always return false"
        );

        $role->role_name = "not_a_boss";
        $this->user->role = $role;

        $this->assertSame(
            false,
            $this->user->isaBoss(),
            "Not-a-Boss should return false"
        );

        $role->role_name = "boss";

        $this->assertSame(
            true,
            $this->user->isaBoss(),
            "Is a boss should return true"
        );
    }
}

