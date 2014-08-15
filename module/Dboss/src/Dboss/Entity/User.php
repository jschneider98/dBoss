<?php

namespace Dboss\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

use Zend\Stdlib\Exception;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter as InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Dboss\PasswordHash;
use Dboss\Xtea;

/**
 * @ORM\Entity (repositoryClass="\Dboss\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 **/
class User extends AbstractEntity implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     **/
    protected $user_id;

    /** @ORM\Column(type="integer", nullable=false) */
    protected $role_id;

    /** @ORM\Column(type="string", unique=true, nullable=false) */
    protected $user_name;

    /** @ORM\Column(type="string", nullable=false) */
    protected $first_name;

    /** @ORM\Column(type="string", nullable=false) */
    protected $last_name;

    /** @ORM\Column(type="string", nullable=false) */
    protected $password;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $modification_date;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $deletion_date;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     **/
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="Query", mappedBy="user")
     **/
    protected $queries;

    /**
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="user")
     **/
    protected $connections;

    protected $fields;
    protected $security;
    protected $input_filter;

    /**
     *
     **/
    public function __construct()
    {
        $this->queries = new ArrayCollection();
        $this->servers = new ArrayCollection();
        $this->connections = new ArrayCollection();
    }

    /**
     * Only the properties that should be hydrated
     **/
    public function getFields()
    {
        if ($this->fields) {
            return $this->fields;
        }

        $this->fields = array(
            'user_id',
            'role_id',
            'user_name',
            'first_name',
            'last_name',
            'password'
        );

        return $this->fields;
    }

    /**
     * 
     **/
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("setInputFilter not used in " . __METHOD__);
    }

    /**
     * 
     **/
    public function getInputFilter()
    {
        if (! $this->input_filter) {
            $input_filter = new InputFilter();
            $factory = new InputFactory();

            $input_filter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'role_id',
                'required' => true,
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'user_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'first_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'last_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'verify_password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $this->input_filter = $input_filter;
        }

        return $this->input_filter;
    }

    /**
     * Magic setter
     **/
    public function __set($property, $value)
    {
        switch ($property) {
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
                $this->$property = $value;
                break;
        }
    }

    /**
     * Magic getter
     **/
    public function __get($property)
    {
        switch ($property) {
            case "user_name":
                return $this->getUserName();
                break;
            default:
                return $this->$property;
                break;
        }
    }

    /**
     * 
     **/
    public function setPassword($password)
    {
        if (is_null($password)) {
            $this->password = null;
            return;
        }

        $salt_key = null;
        $iteration_count = null;
        $portable_hashes = null;

        extract($this->security, EXTR_IF_EXISTS);

        $hasher = new PasswordHash($iteration_count, $portable_hashes);
        $this->password = $hasher->HashPassword($salt_key.$password);
    }

    /**
     * Checks to see if passed password matches the user's password
     * 
     * @param string Password to check
     * @return bool True if the password matches, false otherwise
     */
    public function checkPassword($password = null)
    {
        if (is_null($password)) {
            return false;
        }

        $salt_key = null;
        $iteration_count = null;
        $portable_hashes = null;

        extract($this->security, EXTR_IF_EXISTS);

        $hasher = new PasswordHash($iteration_count, $portable_hashes);
        return $hasher->CheckPassword($salt_key.$password, $this->password);
    }

    /**
     *
     **/
    public function setUserName($user_name)
    {
        if (is_null($user_name)) {
            $this->user_name = null;
            return;
        }

        $xtea = new Xtea($this->security['salt_key']);
        $this->user_name = $xtea->encrypt($user_name);
    }

    /**
     * 
     **/
    public function getUserName()
    {
        if (is_null($this->user_name)) {
            return null;
        }

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
     **/
    public function isMyQuery($query_id = null)
    {
        if (! $this->user_id || ! is_numeric($this->user_id)) {
            return false;
        }

        if (! $query_id || ! is_numeric($query_id)) {
            return false;
        }

        $criteria = new Criteria();

        $criteria->andWhere(
            $criteria->expr()->eq(
                'user_id',
                $this->user_id
            )
        );

        $criteria->andWhere(
            $criteria->expr()->eq(
                'query_id',
                $query_id
            )
        );

        $query = $this->queries->matching($criteria);

        return ($query->count()) ? true : false;
    }

    /**
     * Checks to see if a connection belongs to the user
     * 
     * @param int connection_id
     * 
     * @return boolean
     **/
    public function isMyConnection($connection_id = null)
    {
        if (! $this->user_id || ! is_numeric($this->user_id)) {
            return false;
        }

        if (! $connection_id || ! is_numeric($connection_id)) {
            return false;
        }

        $criteria = new Criteria();

        $criteria->andWhere(
            $criteria->expr()->eq(
                'user_id',
                $this->user_id
            )
        );

        $criteria->andWhere(
            $criteria->expr()->eq(
                'connection_id',
                $connection_id
            )
        );

        $connection = $this->connections->matching($criteria);

        return ($connection->count()) ? true : false;
    }

    /**
     * Gets a user's query (but only if it belongs to the user)
     * 
     * @param int A query_id
     * 
     * @return boolean
     **/
    public function getMyQuery($query_id = null)
    {
        if ( ! $this->user_id || ! is_numeric($this->user_id)) {
            return null;
        }

        if ( ! $query_id || ! is_numeric($query_id)) {
            return null;
        }

        $criteria = new Criteria();

        $criteria->andWhere(
            $criteria->expr()->eq(
                'user_id',
                $this->user_id
            )
        );

        $criteria->andWhere(
            $criteria->expr()->eq(
                'query_id',
                $query_id
            )
        );

        $query = $this->queries->matching($criteria);

        return ($query->count()) ? $query->first() : null;
    }

    /**
     * See if this user is a "Boss" (Admin)
     **/
    public function isaBoss()
    {
        if (! $this->role) {
            return false;
        }

        return ($this->role->role_name == "boss") ? true : false;
    }

    /**
     *  Checks to see if the user has limited access
     **/
    public function isLimited()
    {
        if (! $this->role) {
            return true;
        }

        return ($this->role->role_name == "limited") ? true : false;
    }

    /**
     * Check to see if user can edit certain data connected to a specific user_id
     **/
    public function canEditUser($user_id)
    {
        if (! $user_id) {
            return false;
        }

        return ($this->isaBoss() || $user_id == $this->user_id);
    }

    /**
     * 
     **/
    public function getConnectionInfo()
    {
        $connection_info = array();

        foreach ($this->connections as $connection) {
            $database_names = $connection->getDatabaseNames();

            foreach ($database_names as $database_name) {
                $connection_info[$connection->connection_id . "-" . $database_name] = $database_name . "-" . $connection->host;
            }
        }
        
        asort($connection_info);

        return $connection_info;
    }
}