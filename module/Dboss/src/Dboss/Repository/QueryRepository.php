<?php

namespace Dboss\Repository;

use Doctrine\ORM\EntityRepository;
use Dboss\Entity;

class QueryRepository extends EntityRepository
{
    /**
     * 
     **/
    public function findSavedQueries(array $criteria = array(), array $order_by = array("modification_date" => "DESC"), $limit = 100, $offset = null)
    {
        $user_id = null;
        $search = null;

        extract($criteria, EXTR_IF_EXISTS);

        if (! $user_id) {
            return array();
        }

        $qb = $this->createQueryBuilder('q');
        $qb->where($qb->expr()->isNotNull('q.query_name'));

        $qb->andWhere($qb->expr()->eq("q.user_id", "?1"));
        $qb->setParameter(1, $user_id);

        if (! is_null($search)) {
            $search = "%" . $search . "%";

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like("q.query_name", "?2"),
                    $qb->expr()->like("q.query", "?2")
                )
            );

            $qb->setParameter(2, $search);
        }

        $qb = $this->addOrderBy($qb, $order_by);
        $qb = $this->addLimit($qb, $limit);
        $qb = $this->addOffset($qb, $offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * 
     **/
    public function findHistoricalQueries(array $criteria = array(), array $order_by = array("modification_date" => "DESC"), $limit = 100, $offset = null)
    {
        $user_id = null;
        $search = null;

        extract($criteria, EXTR_IF_EXISTS);

        if (! $user_id) {
            return array();
        }

        $qb = $this->createQueryBuilder('q');
        $qb->where($qb->expr()->isNull('q.query_name'));

        $qb->andWhere($qb->expr()->eq("q.user_id", "?1"));
        $qb->setParameter(1, $user_id);

        if (! is_null($search)) {
            $search = "%" . $search . "%";

            $qb->andWhere(
                $qb->expr()->like("q.query", "?2")
            );

            $qb->setParameter(2, $search);
        }

        $qb = $this->addOrderBy($qb, $order_by);
        $qb = $this->addLimit($qb, $limit);
        $qb = $this->addOffset($qb, $offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * 
     **/
    public function findQueries(array $criteria = array(), array $order_by = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('q');
        $qb->where($qb->expr()->isNotNull('q.query_name'));

        $param_num = 0;

        foreach ($criteria as $condition) {
            $field = null;
            $value = null;
            $operator = 'eq';

            extract($condition, EXTR_IF_EXISTS);

            if (is_null($field) || is_null($value)) {
                continue;
            }

            $param_num++;

            $qb->andWhere($qb->expr()->$operator("q." . $field, "?$param_num"));
            $qb->setParameter($param_num, $value);
        }

        $qb = $this->addOrderBy($qb, $order_by);
        $qb = $this->addLimit($qb, $limit);
        $qb = $this->addOffset($qb, $offset);

        return $qb->getQuery()->getResult();
    }

    /**
     *
     **/
    protected function addOrderBy($qb, $order_by)
    {
        if (is_array($order_by) && count($order_by)) {
            foreach ($order_by as $field => $order) {
                $field = "q.".$field;
                $qb->addOrderBy($field, $order);
            }
        }

        return $qb;
    }

    /**
     *
     **/
    protected function addLimit($qb, $limit)
    {
        if (is_integer($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    /**
     *
     **/
    protected function addOffset($qb, $offset)
    {
        if (is_integer($offset)) {
            $qb->setFirstResult($offset);
        }

        return $qb;
    }
}
