<?php

namespace Dboss\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter as InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Dboss\Schema\Resource\ResourceFactory;
use Dboss\Schema\Resource\Null;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="connection")
 **/
class Connection extends AbstractEntity implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", nullable=false)
     **/
    protected $connection_id;

    /** @ORM\Column(type="integer", nullable=false) */
    protected $user_id;

    /** @ORM\Column(type="string", nullable=true) */
    protected $display_name;

    /** @ORM\Column(type="string", nullable=true) */
    protected $database_name;

    /** @ORM\Column(type="string", nullable=true) */
    protected $user_name;

    /** @ORM\Column(type="string", nullable=true) */
    protected $password;

    /** @ORM\Column(type="string", nullable=true) */
    protected $host;

    /** @ORM\Column(type="string", nullable=true) */
    protected $driver;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $is_server_connection;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $creation_date;

    /** @ORM\Column(type="datetime", nullable=false) */
    protected $modification_date;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="connections")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     **/
    protected $user;

    // Set via event & EntityIngector
    protected $connection_factory;
    protected $input_filter;

    /**
     * Only the properties that should be hydrated
     **/
    public function getFields()
    {
        if ($this->fields) {
            return $this->fields;
        }

        $this->fields = array(
            'connection_id',
            'user_id',
            'display_name',
            'database_name',
            'user_name',
            'password',
            'host',
            'driver',
            'is_server_connection'
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
                'name'     => 'connection_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $input_filter->add($factory->createInput(array(
                'name'     => 'display_name',
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
                'name'     => 'driver',
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
                'name'     => 'database_name',
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
                'name'     => 'host',
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
                'name'     => 'is_server_connection',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Boolean'),
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

            /*
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
            */

            $this->input_filter = $input_filter;
        }

        return $this->input_filter;
    }

    /**
     * Connect to the db
     * 
     * @return Zend\Db\Adapter\Adapter Zf2 db adapter
     **/
    public function connect()
    {
        return $this->connection_factory->getConnection();
    }

    /**
     * 
     **/
    public function getDatabaseNames()
    {
        if (! $this->is_server_connection) {
            return array($this->database_name);
        }

        $db = $this->connect();

        $params = array(
            'resource_type' => 'db',
            'db'            => $db
        );

        $resource_factory = new ResourceFactory($params);
        $schema_resource = $resource_factory->getResource();

        if ($schema_resource instanceof Null) {
            return array();
        }

        $databases = $schema_resource->getEncodedResourceList(array('search' => '%'));

        if (! $databases) {
            return array();
        }

        $database_names = array();

        foreach ($databases as $database) {
            $database_names[] = $database['resource_name'];
        }

        return $database_names;
    }
}