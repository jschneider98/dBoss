<?php

namespace Dboss\Service;

class QueryService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Dboss\Entity\Query';
        parent::__construct($params);
    }

    /**
     * 
     **/
    public function findSavedQueries(array $criteria = array(), array $order_by = array("modification_date" => "DESC"), $limit = 100, $offset = null)
    {
        return $this->getRepository()->findSavedQueries($criteria);
    }

    /**
     * 
     **/
    public function findHistoricalQueries(array $criteria = array(), array $order_by = array("modification_date" => "DESC"), $limit = 100, $offset = null)
    {
        return $this->getRepository()->findHistoricalQueries($criteria);
    }

    /**
     * 
     **/
    public function findQueries(array $criteria = array(), array $order_by = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findSavedQueries($criteria);
    }
}