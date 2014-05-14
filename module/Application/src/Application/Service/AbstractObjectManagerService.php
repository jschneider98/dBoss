<?php
namespace Application\Service;

use Application\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\LockMode;
use Zend\Stdlib\Exception;

abstract class AbstractObjectManagerService
{
    protected $entity_class;
    protected $object_manager;

    public function __construct(array $params = array())
    {
        $entity_class = null;
        $object_manager = null;

        extract($params, EXTR_IF_EXISTS);

        if (! $object_manager || ! $object_manager instanceof EntityManager) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects $object_manager to be an instance of EntityManager',
                __METHOD__
            ));
        }

        if (! $entity_class) {
            throw new Exception\BadMethodCallException(sprintf(
                'Invalid entity_class passed to %s ',
                __METHOD__
            ));
        }

        $this->object_manager = $object_manager;
        $this->entity_class = $entity_class;
    }

    /**
     * 
     **/
    public function find($id, $lock_mode = LockMode::NONE, $lock_version = null)
    {
        return $this->getRepository()->find($id, $lock_mode, $lock_version);
    }

    /**
     * 
     **/
    public function findOrCreate($id, $lock_mode = LockMode::NONE, $lock_version = null)
    {
        if (! is_null($id)) {
            $entity = $this->find($id, $lock_mode, $lock_version);

            if ($entity) {
                return $entity;
            }
        }

        return $this->create();
    }

    /**
     * 
     **/
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * 
     **/
    public function findBy(array $criteria = null, array $order_by = null, integer $limit = null, integer $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $order_by, $limit, $offset);
    }

    /**
     * 
     **/
    public function findOneBy(array $criteria, array $order_by = null)
    {
        return $this->getRepository()->findOneBy($criteria, $order_by);
    }

    /**
     * 
     **/
    public function create()
    {
        $entity_name = $this->getRepository()->getClassName();
        return new $entity_name();
    }

    /**
     * 
     **/
    public function getRepository()
    {
        return $this->object_manager->getRepository($this->entity_class);
    }

    /**
     * 
     **/
    public function save(AbstractEntity $entity)
    {
        $row = null;

        $meta = $this->object_manager->getClassMetadata(get_class($entity));
        $identifier = $meta->getSingleIdentifierFieldName();

        if (! is_null($entity->{$identifier})) {
            $row = $this->find($entity->{$identifier});
        }

        if (! $row) {
            $this->object_manager->detach($entity);
            $this->object_manager->persist($entity);
        }

        $this->object_manager->flush();
    }

    /**
     * 
     **/
    public function delete(AbstractEntity $entity)
    {
        // soft delete
        if (property_exists($entity, 'deletion_date')) {
            $entity->deletion_date = new \DateTime("now");
            $this->save($entity);
            return;
        }

        // hard delete
        $this->object_manager->detach($entity);
        $this->object_manager->remove($entity);
        $this->object_manager->flush();
    }
}