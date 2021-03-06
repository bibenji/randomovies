<?php

namespace Randomovies\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Randomovies\Entity\MovieSearch;
use Randomovies\Tool\{ETLParamsBuilder, Query, BoolBuilder, ShouldBuilder};
use Randomovies\Form\MovieSearchType;
use Randomovies\Entity\Movie;

class SearchController extends Controller
{
    /**
     * @Route("/recherche", name="search")
     */
    public function searchAction(Request $request)
    {
        $movieSearch = new MovieSearch();
        
        if ($request->request->get('title')) {
            $movieSearch->setTitle($request->request->get('title'));
        }
        
        $genreOptions = array_map(function($elem) { return $elem['genre']; }, $this->getDoctrine()->getRepository(Movie::class)->getDistinctCategories());        
        $genreOptions = array_combine($genreOptions, $genreOptions);
        
        $movieSearchForm = $this->createForm(MovieSearchType::class, $movieSearch, [
            'genre_options' => $genreOptions,            
        ]);        

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
    	$etlClient = $this->get('elasticsearch_client');
    	
    	$builder = new ETLParamsBuilder();
    	$builder->setIndex($this->get('elasticsearch_client')->getIndexForMovies());
    	$builder->setType('movie');
    	
    	$query = new Query();
    	$bool = new BoolBuilder();
    	
    	if (null !== $movieSearch->getTitle()) {
			$explodedTitle = explode(' ', $movieSearch->getTitle());
			foreach ($explodedTitle as $partOfExplodedTitle) {
				$bool->addMust(['term' => ['title' => strtolower($partOfExplodedTitle)]]);
			}
    	}
    	
    	if (null !== $movieSearch->getGenre() && '' !== $movieSearch->getGenre()) {
    	    $bool->addMust(['term' => ['genre.keyword' => $movieSearch->getGenre()]]);
    	}
    	
    	if (null !== $movieSearch->getKeyWords()) {
			$should = new ShouldBuilder();
			$explodedKeyWords = explode(' ', $movieSearch->getKeyWords());
			foreach ($explodedKeyWords as $keyWord) {
				$should->addTerm(['title' => strtolower($keyWord)]);
    	    	$should->addTerm(['director' => strtolower($keyWord)]);
    	    	$should->addTerm(['actors' => strtolower($keyWord)]);
			}    	    
    	    $bool->addShould($should);
    	}
    	
    	$today = new \DateTime();
    	$currentYear = $today->format('Y');
    	$yearFrom = $movieSearch->getYearFrom()->format('Y') ?? 1900;
    	$yearTo = $movieSearch->getYearTo()->format('Y') ?? $currentYear;
    	$bool->addMust(['range' => ['year' => ['gt' => $yearFrom, 'lte' => $yearTo]]]);
    	
    	$durationFrom = $movieSearch->getDurationFrom() ?? 0;
    	$durationTo = $movieSearch->getDurationTo() ?? 600;
    	$bool->addMust(['range' => ['duration' => ['gt' => $durationFrom, 'lt' => $durationTo]]]);
    	
    	$ratingMin = $movieSearch->getRatedMin() ?? 1;
    	$ratingMax = $movieSearch->getRatedMax() ?? 5;
    	$bool->addMust(['range' => ['rating' => ['gte' => $ratingMin, 'lte' => $ratingMax]]]);
    	
    	$query->addBool($bool);    	
    	$builder->addSize(10);
    	$builder->addQuery($query);
		
    	$result = $etlClient->search($builder->getParams());    	
        
    	$ids = [];    	
    	foreach ($result['hits']['hits'] as $hit) {
    		$ids[] = $hit['_id'];
    	}
    	    	
    	$moviesRepository = $this->getDoctrine()->getRepository('Randomovies:Movie');
    	
    	$results = $moviesRepository->getMoviesWithIds($ids);
    	
        return $results;
    }
}
