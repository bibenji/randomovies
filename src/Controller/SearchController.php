<?php

namespace Randomovies\Controller;

use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Elastica\Query\Range;
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
        $finder = $this->container->get('fos_elastica.finder.randomovies.movie');

        $boolQuery = new BoolQuery();

        if (null !== $movieSearch->getGenre() && '' !== $movieSearch->getGenre()) {
            $genreQuery = new Terms();
            $genreQuery->setTerms('genre', [strtolower($movieSearch->getGenre())]);
            $boolQuery->addMust($genreQuery);
        }

        if (null !== $movieSearch->getTitle()) {
            $titleQuery = new Match();
            $titleQuery->setFieldQuery('title', $movieSearch->getTitle());
            $boolQuery->addMust($titleQuery);
        }

        if (null !== $movieSearch->getKeyWords()) {
            $researchKeyWords = explode(' ', $movieSearch->getKeyWords());
            $synopsisQuery = new Terms();
            $synopsisQuery->setTerms('synopsis', $researchKeyWords);
            $directorQuery = new Terms();
            $directorQuery->setTerms('director', $researchKeyWords);
            $actorsQuery = new Terms();
            $actorsQuery->setTerms('actors', $researchKeyWords);
            $subBoolQuery = new BoolQuery();
            $subBoolQuery->addShould($synopsisQuery);
            $subBoolQuery->addShould($directorQuery);
            $subBoolQuery->addShould($actorsQuery);
            $boolQuery->addFilter($subBoolQuery);
        }
        
        $today = new \DateTime();        
        $currentYear = $today->format('Y');

        $yearFrom = $movieSearch->getYearFrom()->format('Y') ?? 1900;
        $yearTo = $movieSearch->getYearTo()->format('Y') ?? $currentYear;
        $yearRange = new Range();
        $yearRange->addField('year', [
            'gte' => $yearFrom,
            'lte' => $yearTo,
        ]);
        $boolQuery->addMust($yearRange);

        $durationFrom = $movieSearch->getDurationFrom() ?? 0;
        $durationTo = $movieSearch->getDurationTo() ?? 600;        
        $durationRange = new Range();
        $durationRange->addField('duration', [
            'gte' => $durationFrom,
            'lte' => $durationTo,
        ]);
        $boolQuery->addMust($durationRange);

        $ratingMin = $movieSearch->getRatedMin() ?? 1;
        $ratingMax = $movieSearch->getRatedMax() ?? 5;        
        $ratingRange = new Range();
        $ratingRange->addField('rating', [
            'gte' => $ratingMin,
            'lte' => $ratingMax,
        ]);
        $boolQuery->addMust($ratingRange);

        return $finder->find($boolQuery);
    }
}
