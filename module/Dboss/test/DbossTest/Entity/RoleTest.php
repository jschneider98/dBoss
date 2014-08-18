<?php
namespace DbossTest\Entity;

use Dboss\Entity\Role;
use PHPUnit_Framework_TestCase;

class RoleTest extends PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testRoleInitialState()
    {
        $role = new Role();

        $this->assertNull(
            $role->role_id,
            "role_id should initially be null"
        );
        
        $this->assertNull(
            $role->role_level,
            "role_level should initially be null"
        );
        
        $this->assertNull(
            $role->role_name,
            "role_name should initially be null"
        );
        
        $this->assertNull(
            $role->display_name,
            "display_name should initially be null"
        );
        
        $this->assertNull(
            $role->creation_date,
            "creation_date should initially be null"
        );
        
        $this->assertNull(
            $role->modification_date,
            "modification_date should initially be null"
        );
        
        $this->assertNull(
            $role->deletion_date,
            "deletion_date should initially be null"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $role = new Role();
        $date = new \DateTime('now');

        $data  = array(
            'role_id'           => 123,
            'role_level'        => 1,
            'role_name'         => 'boss',
            'display_name'      => 'Boss',
            'creation_date'     => $date,
            'modification_date' => $date,
            'deletion_date'     => $date
        );

        $role->exchangeArray($data);

        $this->assertSame(
            $data['role_id'],
            $role->role_id,
            "role_id was not set correctly"
        );

        $this->assertSame(
            $data['role_level'],
            $role->role_level,
            "role_level was not set correctly"
        );

        $this->assertSame(
            $data['role_name'],
            $role->role_name,
            "role_name was not set correctly"
        );

        $this->assertSame(
            $data['display_name'],
            $role->display_name,
            "display_name was not set correctly"
        );
    }

    /**
     * 
     */
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $role = new Role();
        $date = new \DateTime('now');

        $data  = array(
            'role_id'           => 123,
            'role_level'        => 1,
            'role_name'         => 'boss',
            'display_name'      => 'Boss',
            'creation_date'     => $date,
            'modification_date' => $date,
            'deletion_date'     => $date
        );

        $role->exchangeArray($data);
        $role->exchangeArray(array());

        $this->assertNull(
            $role->role_id,
            "role_id should have been set to null"
        );
        
        $this->assertNull(
            $role->role_level,
            "role_level should have been set to null"
        );
        
        $this->assertNull(
            $role->role_name,
            "role_name should have been set to null"
        );
        
        $this->assertNull(
            $role->display_name,
            "display_name should have been set to null"
        );
        
        $this->assertNull(
            $role->creation_date,
            "creation_date should have been set to null"
        );
        
        $this->assertNull(
            $role->modification_date,
            "modification_date should have been set to null"
        );
        
        $this->assertNull(
            $role->deletion_date,
            "deletion_date should have been set to null"
        );
    }

    /**
     * 
     */
    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $role = new Role();
        $date = new \DateTime('now');

        $data  = array(
            'role_id'           => 123,
            'role_level'        => 1,
            'role_name'         => 'boss',
            'display_name'      => 'Boss',
            'creation_date'     => $date,
            'modification_date' => $date,
            'deletion_date'     => $date
        );

        $role->exchangeArray($data);
        $copy = $role->getArrayCopy();

        $this->assertSame(
            $copy['role_id'],
            $role->role_id,
            "role_id was not set correctly"
        );

        $this->assertSame(
            $copy['role_level'],
            $role->role_level,
            "role_level was not set correctly"
        );

        $this->assertSame(
            $copy['role_name'],
            $role->role_name,
            "role_name was not set correctly"
        );

        $this->assertSame(
            $copy['display_name'],
            $role->display_name,
            "display_name was not set correctly"
        );
    }
}