<?php

namespace Randomovies\Repository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
	public function getDistinctTags()
	{
		return $this->getEntityManager()
			->createQueryBuilder()
			->select('t')
			->from('Randomovies:Tag', 't')
			->orderBy('t.name', 'ASC')
            ->indexBy('t', 't.id')
            ->distinct()
			->getQuery()
			->getResult()			
		;
	}	
	
	public function getTotalTags()
	{
	    return $this->getEntityManager()
	        ->createQueryBuilder()
	        ->select('COUNT(t)')
	        ->from('Randomovies:Tag', 't')
            ->getQuery()
            ->getSingleScalarResult()
	    ;
	}
}
