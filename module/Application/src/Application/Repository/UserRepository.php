<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity;

class UserRepository extends EntityRepository
{
    /**
     * 
     **/
    public function findActiveUsers() {
        $criteria = array('deletion_date' => null);
        return $this->findBy($criteria);
    }

    /**
     * 
     **/
    public function findInactiveUsers() {
        $qb = $this->createQueryBuilder('u');
        $qb->where($qb->expr()->isNotNull('u.deletion_date'));

        return $qb->getQuery()->getResult();
    }
}
