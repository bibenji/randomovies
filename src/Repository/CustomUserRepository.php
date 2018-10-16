<?php

namespace Randomovies\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class CustomUserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    /**
     * Used for ETL
     *
     * @param unknown $min
     * @param unknown $max
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getUsersForETL($min, $max)
    {
    	return $this->createQueryBuilder('u')	    	
	    	->setFirstResult($min)
	    	->setMaxResults($max)
    		->getQuery()
    		->getResult()
    	;
    }
    
    /**
     * Used for ETL
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getMaxUsersId()
    {
    	return $this->createQueryBuilder('u')
	    	->select('COUNT(u)')
	    	->getQuery()
	    	->getSingleScalarResult()
    	;
    }
}
