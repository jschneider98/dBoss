<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity;

class QueryRepository extends EntityRepository
{
    /**
     * 
     **/
    public function findSavedQueries(array $criteria = array(), array $order_by = null, integer $limit = null, integer $offset = null)
    {
        $qb = $this->createQueryBuilder('q');
        $qb->where($qb->expr()->isNotNull('q.query_name'));

        foreach ($criteria as $field => $value) {
            $qb->andWhere($qb->expr()->eq("q." . $field, $value));
        }

        return $qb->getQuery()->getResult();
    }
}
