<?php

namespace Dboss\Repository;

use Doctrine\ORM\EntityRepository;
use Dboss\Entity;
use Dboss\Xtea;

class UserRepository extends EntityRepository
{
    /**
     * 
     **/
    public function findActiveUsers()
    {
        $criteria = array('deletion_date' => null);
        return $this->findBy($criteria);
    }

    /**
     * 
     **/
    public function findInactiveUsers()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where($qb->expr()->isNotNull('u.deletion_date'));

        return $qb->getQuery()->getResult();
    }

    /**
     * 
     */
    public function findByUserName($security, $user_name = null)
    {
        if (is_null($user_name)) {
            return;
        }

        $xtea = new Xtea($security['salt_key']);
        $encrypted_user_name = $xtea->encrypt($user_name);

        $criteria = array('user_name' => $encrypted_user_name);
        return $this->findOneBy($criteria);
    }
}
