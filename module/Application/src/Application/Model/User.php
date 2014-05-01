<?php

namespace Application\Model;

use Zend\Stdlib\Exception;
use Dboss\Xtea;

class User extends AbstractEntity
{
    public $user_id;
    public $role_id;
    protected $user_name;
    public $first_name;
    public $last_name;
    protected $password;
    public $creation_date;
    public $modification_date;
    public $deletion_date;

    protected $security;

    public function __construct(array $params = array())
    {
        parent::__construct($params);

        $security = null;
        extract($params, EXTR_IF_EXISTS);

        $this->security = $security;
    }

    /**
     * Magic setter
     **/
    public function __set($field_name, $value)
    {
        switch ($field_name) {
            case "password":
                $this->setPassword($value);
                break;
            case "user_name":
                $this->setUserName($value);
                break;
            case "security":
                $this->setSecurity($value);
                break;
            default:
                $this->$field_name = $value;
                break;
        }
    }

    /**
     * Magic getter
     **/
    public function __get($field_name)
    {
        switch ($field_name) {
            case "user_name":
                return $this->getUserName();
                break;
            default:
                return $this->$field_name;
                break;
        }
    }

    /**
     * 
     **/
    public function setPassword($password)
    {
        $salt_key = null;
        $iteration_count = null;
        $portable_hashes = null;

        extract($this->security, EXTR_IF_EXISTS);

        $hasher = new \Dboss\PasswordHash($iteration_count, $portable_hashes);
        $this->password = $hasher->HashPassword($salt_key.$password);
    }

    /**
     *
     **/
    public function setUserName($user_name)
    {
        $xtea = new Xtea($this->security['salt_key']);
        $this->user_name = $xtea->encrypt($user_name);
    }

    /**
     * 
     **/
    public function getUserName()
    {
        $xtea = new Xtea($this->security['salt_key']);
        return $xtea->decrypt($this->user_name);
    }

    /**
     * 
     **/
    public function getRawUserName()
    {
        return $this->user_name;
    }

    /**
     * 
     **/
    public function setSecurity(array $security = array())
    {
        $salt_key = null;
        $iteration_count = 8;
        $portable_hashes = 0;

        extract($security, EXTR_IF_EXISTS);

        if ( ! $salt_key) {
            throw new Exception\BadMethodCallException('Missing salt_key. Invalid security option passed to ' . __METHOD__);
        }

        $this->security = array(
            'salt_key'        => $salt_key,
            'iteration_count' => $iteration_count,
            'portable_hashes' => $portable_hashes,
        );
    }

    /**
     * Checks to see if a query belongs to the user
     * 
     * @param int A query_id
     * 
     * @return boolean
     * */
    public function isMyQuery($query_id = null)
    {
        if ( ! $this->user_id || ! is_numeric($this->user_id)) {
            return false;
        }

        if ( ! $query_id || ! is_numeric($query_id)) {
            return false;
        }

        //$params = array('where' => "user_id = {$this->user_id} AND query_id = {$query_id}");
        //$query = Application_Model_DbTable_Row_Query::getRow($params);
        $query = true;

        return ($query) ? true : false;
    }

    /**
     * Checks to see if a database belongs to the user
     * 
     * @param int A database_id
     * 
     * @return boolean
     * */
    public function isMyDatabase($database_id = null)
    {

        if ( ! $this->user_id || ! is_numeric($this->user_id)) {
            return false;
        }

        if ( ! $database_id || ! is_numeric($database_id)) {
            return false;
        }

        //$params = array('where' => "user_id = {$this->user_id} AND database_id = {$database_id}");
        //$query = Application_Model_DbTable_Row_Database::getRow($params);
        $query = true;

        return ($query) ? true : false;
    }

    /**
     * Checks to see if a server belongs to the user
     * 
     * @param int A server_id
     * 
     * @return boolean
     * */
    public function isMyServer($server_id = null)
    {

        if ( ! $this->user_id || ! is_numeric($this->user_id)) {
            return false;
        }

        if ( ! $server_id || ! is_numeric($server_id)) {
            return false;
        }

        //$params = array('where' => "user_id = {$this->user_id} AND server_id = {$server_id}");
        //$query = Application_Model_DbTable_Row_Server::getRow($params);
        $query = true;

        return ($query) ? true : false;
    }

    /**
     * Gets a user's query (but only if it belongs to that user)
     * 
     * @param int A query_id
     * 
     * @return boolean
     * */
    public function getMyQuery($query_id = null)
    {

        if ( ! $this->user_id || ! is_numeric($this->user_id)) {
            return null;
        }

        if ( ! $query_id || ! is_numeric($query_id)) {
            return null;
        }

        //$params = array('where' => "user_id = {$this->user_id} AND query_id = {$query_id}");
        //return Application_Model_DbTable_Row_Query::getRow($params);
    }

    /**
     * See if this user is a "Boss" (Admin)
     **/
    public function isaBoss()
    {
        // $role_db_row = Application_Model_DbTable_Row_Role::getOrCreateRow(array("where" => "role_id = " . $this->role_id));
        // return ($role_db_row->role_name == "boss") ? true : false;
        return true;
    }

    /**
     *  Checks to see if the user has limited access
     **/
    public function isLimited()
    {
        //$role_db_row = Application_Model_DbTable_Row_Role::getOrCreateRow(array("where" => "role_id = " . $this->role_id));
        //return ($role_db_row->role_name == "limited") ? true : false;
        return false;
    }

    /**
     * Check to see if user can edit certain data connected to a specific user_id
     **/
    public function canEditUser($user_id)
    {
        return ($this->isaBoss() || $user_id == $this->user_id);
    }
}