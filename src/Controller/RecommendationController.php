<?php

namespace Randomovies\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    	
    	$baseJson = '{"query":{"terms":{"likes":[]}},"size":0,"aggregations":{"movies_like_likes":{"significant_terms":{"field":"likes","min_doc_count": 1,"size":10}}}}';
    	
    	$body = json_decode($baseJson, TRUE);
    	
    	$body['query']['terms']['likes'] = $likes;    	
    	
    	$etlClient = $this->get('elasticsearch_client');    	    	
    	    	
    	$params = [
    			'index' => $etlClient->getIndexForUsers(),
    			'type' => 'user',
    			'body' => $body
    	];
    	
    	dump(json_encode($params));
    	
    	$result = $etlClient->search($params);    	
    	
    	dump($result);
        
    	$ids = [];
    	
    	foreach ($result['aggregations']['movies_like_likes']['buckets'] as $bucket) {
    		$ids[] = $bucket['key'];
    	}
    	
    	dump($ids);
    	
    	$moviesRepository = $this->getDoctrine()->getRepository('Randomovies:Movie');
    	
    	$results = $moviesRepository->getMoviesWithIds($ids);
    	
    	dump($results);

        return $results;
    }
}
