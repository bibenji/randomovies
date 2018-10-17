<?php

namespace Randomovies\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Randomovies\Entity\MovieSearch;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $movieSearch = new MovieSearch();

        $movieSearchForm = $this->get('form.factory')
            ->createNamed(
                '',
                'Randomovies\Form\MovieSearchType',
                $movieSearch,
                [
                    'action' => $this->generateUrl('search'),
                    'method' => 'POST'
                ]
            );

        $movieSearchForm->handleRequest($request);
        $movieSearch = $movieSearchForm->getData();

        $results = [];
		        
        if ($request->getMethod() === Request::METHOD_POST) {
            $results = $this->getResultsForSearch($movieSearch);
        }

        return $this->render('search/search.html.twig', [
            'results' => $results,
            'movieSearchForm' => $movieSearchForm->createView(),
        ]);
    }

    private function getResultsForSearch(MovieSearch $movieSearch)
    {
    	// @todo : faire un tool pour construire la query elasticsearch
    	
    	$etlClient = $this->get('elasticsearch_client');
    	
    	$baseJson = '
			{
				"query": {
					"bool": {
						"must": [%s],
						"must_not": [],
						"should": []
					}
				},				
				"size": 10
			}
		'; 
    	
    	$must = '';
    	$should = '';
    	

    	if (null !== $movieSearch->getTitle()) {
    		$must .= sprintf('{"term":{"title":"%s"}},', $movieSearch->getTitle());
    	}
    	
    	if (null !== $movieSearch->getGenre() && '' !== $movieSearch->getGenre()) {    		
    		$must .= sprintf('{"term":{"genre.keyword":"%s"}},', $movieSearch->getTitle());
    	}
    	
    	if (null !== $movieSearch->getKeyWords()) {    		
    		$should .= sprintf('{"match":{"title": "%s"}},{"match":{"director": "%s"}},{"match":{"actors": "%s"}},', $movieSearch->getKeyWords(), $movieSearch->getKeyWords(), $movieSearch->getKeyWords());
    	}

    	$today = new \DateTime();
    	$currentYear = $today->format('Y');    	
    	$yearFrom = $movieSearch->getYearFrom()->format('Y') ?? 1900;
    	$yearTo = $movieSearch->getYearTo()->format('Y') ?? $currentYear;    	    
    	$must .= sprintf('{"range":{"year":{"gt":"%s","lt":"%s"}}},', $yearFrom, $yearTo);
    	
    	$durationFrom = $movieSearch->getDurationFrom() ?? 0;
    	$durationTo = $movieSearch->getDurationTo() ?? 600;    	
    	$must .= sprintf('{"range":{"duration":{"gt":"%s","lt":"%s"}}},', $durationFrom, $durationTo);
    	
    	$ratingMin = $movieSearch->getRatedMin() ?? 1;
    	$ratingMax = $movieSearch->getRatedMax() ?? 5;    	
    	$must .= sprintf('{"range":{"rating":{"gt":"%s","lt":"%s"}}},', $ratingMin, $ratingMax);    	   	
    	
    	$must = rtrim($must, ',');
    	$should = rtrim($should, ',');
    	
    	$finalJson = sprintf($baseJson, $must, $should);
    	
    	$body = json_decode($finalJson, TRUE);
    	
    	$params = [
    			'index' => $etlClient->getIndexForMovies(),
    			'type' => 'movie',
    			'body' => $body
    	];
    	
    	dump($params);
    	
    	$result = $etlClient->search($params);    	
    	
    	dump($result);
        
    	$ids = [];
    	
    	foreach ($result['hits']['hits'] as $hit) {
    		$ids[] = $hit['_id'];
    	}
    	
    	dump($ids);
    	
    	$moviesRepository = $this->getDoctrine()->getRepository('Randomovies:Movie');
    	
    	$results = $moviesRepository->getMoviesWithIds($ids);
    	
    	dump($results);

        return $results;
    }
}
