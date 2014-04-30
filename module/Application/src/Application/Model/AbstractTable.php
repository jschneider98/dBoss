<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Exception;

abstract class AbstractTable
{
    protected $pkey;
    protected $table_gateway;

    public function __construct(array $params = array())
    {
        $pkey = null;
        $table_gateway = null;

        extract($params, EXTR_IF_EXISTS);

        if ( ! $table_gateway || ! $table_gateway instanceof TableGateway) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects $table_gateway to be an instance of TableGateway',
                __METHOD__
            ));
        }

        if ( ! $pkey) {
            throw new Exception\BadMethodCallException(sprintf(
                'Invalid pkey passed to %s ',
                __METHOD__
            ));
        }

        $this->table_gateway = $table_gateway;
        $this->pkey = $pkey;
    }

    /**
     * 
     **/
    public function fetchAll($where = null)
    {
        return $this->table_gateway->select($where);
    }

    /**
     * 
     **/
    public function fetchRow($id)
    {
        if ( ! $id) {
            return null;
        }

        $rowset = $this->table_gateway->select(array($this->pkey => $id));
        return $rowset->current();
    }

    /**
     * 
     **/
    public function save(AbstractEntity $entity)
    {
        if (property_exists($entity,'creation_date') && ! isset($entity->creation_date)) {
            $entity->creation_date = date("Y-m-d H:i:s");
        }

        if (property_exists($entity,'modification_date')) {
            $entity->modification_date = date("Y-m-d H:i:s");
        }

        $data = $entity->getArrayCopy();

        $id = $entity->{$this->pkey};
        $row = $this->fetchRow($id);

        if ( ! $row) {
            $this->table_gateway->insert($data);
            // update entity's pkey
            $entity->{$this->pkey} = $this->table_gateway->getLastInsertValue();
        } else {
            $this->table_gateway->update($data, array($this->pkey => $id));
        }
    }

    /**
     * 
     **/
    public function delete(AbstractEntity $entity)
    {
        $id = $entity->{$this->pkey};

        // soft delete
        if (property_exists($entity, 'deletion_date')) {
            $entity->deletion_date = date("Y-m-d H:i:s");
            $this->save($entity);
            return;
        }

        // hard delete
        $this->table_gateway->delete(array($this->pkey => $id));
        // update entity (no data)
        $entity->exchangeArray();
    }
}