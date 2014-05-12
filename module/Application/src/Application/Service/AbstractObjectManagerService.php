<?php
namespace Application\Service;

use Application\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
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
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * 
     **/
    public function findBy($criteria = null)
    {
        return $this->getRepository()->findBy($criteria);
    }

    /**
     * 
     **/
    public function findOneBy($criteria = null)
    {
        return $this->getRepository()->findOneBy($criteria);
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
        $this->object_manager->detach($entity);

        $meta = $this->object_manager->getClassMetadata(get_class($entity));
        $identifier = $meta->getSingleIdentifierFieldName();

        $criteria = array(
            $identifier => $entity->{$identifier}
        );

        $row = $this->findOneBy($criteria);

        if (! $row) {
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