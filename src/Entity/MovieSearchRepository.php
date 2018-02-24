<?php

namespace Randomovies\Entity;

use FOS\ElasticaBundle\Repository;
use Randomovies\Entity\MovieSearch;

class MovieSearchRepository extends Repository
{
    public function search(MovieSearch $movieSearch)
    {
		/* test */
		$query_part = new \Elastica\Query\Bool();
		$query_part->addShould(
			new \Elastica\Query\Term(array('title' => array('value' => $movieSearch->getTitle(), 'boost' => 3)))
		);
		// $query_part->addShould(
			// new \Elastica\Query\Term(array('markdown_body' => array('value' => 'introduction')))
		// );
		// $filters = new \Elastica\Filter\Bool();
		// $filters->addMust(
			// new \Elastica\Filter\Term(array('language' => 'fr'))
		// );
		// $filters->addMust(
			// new \Elastica\Filter\NumericRange('published_at', array(
				// 'lte' => date('c'),
			// ))
		// );
		// $query = new \Elastica\Query\Filtered($query_part, $filters);
		return $this->find($query_part);
		// return $type->search($query);
	
		
		// -----------------
		
	
        if ($movieSearch->getTitle() != null && $movieSearch->getTitle() != '') {			
			$fieldQuery = new \Elastica\Query\Match();			
            $fieldQuery->setFieldQuery('title', $movieSearch->getTitle());
            $fieldQuery->setFieldFuzziness('title', 0.5);
            $fieldQuery->setFieldMinimumShouldMatch('title', '25%');            
			
        }
		else {
            $fieldQuery = new \Elastica\Query\MatchAll();
        }
		
		// $baseQuery = $fieldQuery;
		// $boolQuery = new \Elastica\Query\Bool();
		// $filtered = new \Elastica\Query\Filtered($baseQuery, $boolQuery);
        // $query = \Elastica\Query::create($filtered);
        // return $this->find($query);
		
		$boolQuery = new \Elastica\Query\Bool();
		$boolQuery->addShould($fieldQuery);
		
		return $this->find($boolQuery);
		
		
        // then we create filters depending on the chosen criterias
        // $boolFilter = new \Elastica\Filter\Bool();

        /*
            Dates filter
            We add this filter only the getIspublished filter is not at "false"
        */
		/*
        if("false" != $articleSearch->getIsPublished()
           && null !== $articleSearch->getDateFrom()
           && null !== $articleSearch->getDateTo())
        {
            $boolFilter->addMust(new \Elastica\Filter\Range('publishedAt',
                array(
                    'gte' => \Elastica\Util::convertDate($articleSearch->getDateFrom()->getTimestamp()),
                    'lte' => \Elastica\Util::convertDate($articleSearch->getDateTo()->getTimestamp())
                )
            ));
        }
		*/
		
        // Published or not filter
        /*
		if($articleSearch->getIsPublished() !== null){
            $boolFilter->addMust(
                new \Elastica\Filter\Terms('published', array($articleSearch->getIsPublished()))
            );
        }

        $filtered = new \Elastica\Query\Filtered($baseQuery, $boolFilter);

        $query = \Elastica\Query::create($filtered);
		*/				
		
        // return $this->find($query);
		// return $this->find('inception');
    }
}