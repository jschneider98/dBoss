<?php

namespace Application\Service;

class QueryService extends AbstractObjectManagerService
{
    public function __construct(array $params = array())
    {
        $params['entity_class'] = '\Application\Entity\Query';
        parent::__construct($params);
    }

    /**
     * 
     **/
    public function findSavedQueries(array $criteria = array(), array $order_by = null, integer $limit = null, integer $offset = null)
    {
        return $this->getRepository()->findSavedQueries($criteria);
    }
}