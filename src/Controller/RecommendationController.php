<?php

namespace Randomovies\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Randomovies\Tool\{ETLParamsBuilder, Query};

class RecommendationController extends Controller
{
    /**
     * @Route("/recommandations", name="recommendations")
     */
    public function RecommendationAction()
    {	
		$recommendations = $this->getResultsForRecommendations();

        return $this->render('recommendation/recommendation.html.twig', [
            'recommendations' => $recommendations,
        ]);
    }

    private function getResultsForRecommendations()    
    {
    	$likes = [];
    	foreach ($this->getUser()->getComments() as $comment) {
    		if ($comment->getNote() >= 4) {
    			$likes[] = $comment->getMovie()->getId();
    		}    		
    	}
    	
    	$etlClient = $this->get('elasticsearch_client');
    	
    	$builder = new ETLParamsBuilder();
    	$builder->setIndex($this->get('elasticsearch_client')->getIndexForUsers());
    	$builder->setType('user');
    	
    	$query = new Query();
    	$query->addTerms(['likes' => $likes]);
    	$builder->addQuery($query);
    	
    	$builder->addSize(0);
    	$builder->addAggregation('movies_like_likes', 'significant_terms', [
    		'field' => 'likes',
    		'min_doc_count' => 1,
    		'size' => 12,
    		'exclude' => $likes
    	]);
    	
    	$result = $etlClient->search($builder->getParams());    	
        
    	$ids = [];
    	
    	foreach ($result['aggregations']['movies_like_likes']['buckets'] as $bucket) {
    		$ids[] = $bucket['key'];
    	}
    	
    	$moviesRepository = $this->getDoctrine()->getRepository('Randomovies:Movie');
    	
    	$results = $moviesRepository->getMoviesWithIds($ids);

        return $results;
    }
}
