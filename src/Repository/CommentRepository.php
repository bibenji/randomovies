<?php

namespace Randomovies\Repository;

/**
 * CommentRepository 
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get Comments Data
     * 
     * Return totalMovies and usersNote for one movie
     * 
     * @param integer $movieId
     * @return array
     */
    public function getCommentsData($movieId)
	{
        $select = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(c) totalComments, AVG(c.note) usersNote')
            ->from('Randomovies:Comment', 'c')
            ->leftJoin('c.movie', 'm')
            ->where('m.id = :movie_id')
            ->setParameter('movie_id', $movieId)
        ;
        
        return $select
            ->getQuery()
            ->getResult()
        ;        
	}
	
	public function getCommentsForUser($userId)
	{
	    $select = $this->getEntityManager()
	       ->createQueryBuilder()
	       ->select('c')
	       ->from('Randomovies:Comment', 'c')
	       ->leftJoin('c.user', 'u')
	       ->where('u.id = :user_id')
	       ->setParameter('user_id', $userId)
	    ;
	    
	    return $select
	       ->getQuery()
	       ->getResult()
	    ;	    
	}
	
	public function getTotalCommentsForUser($userId)
	{
	    $select = $this->getEntityManager()
	       ->createQueryBuilder()
	       ->select('COUNT(c)')
	       ->from('Randomovies:Comment', 'c')
	       ->leftJoin('c.user', 'u')
	       ->where('u.id = :user_id')
	       ->setParameter('user_id', $userId)
	    ;
	    
	    return $select
	       ->getQuery()
	       ->getSingleScalarResult()
	    ;
	}
}
