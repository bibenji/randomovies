<?php

namespace Randomovies\Controller;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Filtered;
use Elastica\Query\Match;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Elastica\Query\Range;
use Elastica\Aggregation\Terms as AggregationTerms;
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

        if (null !== $movieSearch->getSynopsis()) {
            $synopsisWords = explode(' ', $movieSearch->getSynopsis());
            $synopsisQuery = new Terms();
            $synopsisQuery->setTerms('synopsis', $synopsisWords);
            $boolQuery->addFilter($synopsisQuery);
        }

        $yearFrom = $movieSearch->getYearFrom()->format('Y') ?? 1900;
        $yearTo = $movieSearch->getYearTo()->format('Y') ?? 2100;
        $ratingQuery = new Range();
        $ratingQuery->addField('year', [
            'gte' => $yearFrom,
            'lte' => $yearTo,
        ]);
        $boolQuery->addMust($ratingQuery);

        $durationFrom = $movieSearch->getDurationFrom() ?? 0;
        $durationTo = $movieSearch->getDurationTo() ?? 340;
        $ratingQuery = new Range();
        $ratingQuery->addField('duration', [
            'gte' => $durationFrom,
            'lte' => $durationTo,
        ]);
        $boolQuery->addMust($ratingQuery);

        $ratingMin = $movieSearch->getRatedMin() ?? 1;
        $ratingMax = $movieSearch->getRatedMax() ?? 5;
        $ratingQuery = new Range();
        $ratingQuery->addField('rating', [
            'gte' => $ratingMin,
            'lte' => $ratingMax,
        ]);
        $boolQuery->addMust($ratingQuery);

        return $finder->find($boolQuery);
    }

    /**
     * @Route("/search_test", name="searchTest")
     */
    public function searchTestAction(Request $request)
    {
        $finder = $this->container->get('fos_elastica.finder.randomovies.movie');
//        $results = $finder->find('Showgirls');

        $boolQuery = new BoolQuery();

        $genreQuery = new Terms();
        $genreQuery->setTerms('genre', ['drame']);
        $boolQuery->addMust($genreQuery);

        $titleQuery = new Match();
        $titleQuery->setFieldQuery('title', 'tree');
        $boolQuery->addMust($titleQuery);

        $synopsisQuery = new Terms();
        $synopsisQuery->setTerms('synopsis', [
            'madame', 'banane'
        ]);
        $boolQuery->addFilter($synopsisQuery);
//
        $ratingQuery = new Range();
        $ratingQuery->addField('year', [
            'gte' => 1900,
            'lte' => 2100,
        ]);
        $boolQuery->addMust($ratingQuery);

        $ratingQuery = new Range();
        $ratingQuery->addField('duration', [
            'gte' => 0,
            'lte' => 280,
        ]);
        $boolQuery->addMust($ratingQuery);

        $ratingQuery = new Range();
        $ratingQuery->addField('rating', [
            'gte' => 1,
            'lte' => 4,
        ]);
        $boolQuery->addMust($ratingQuery);



        $results = $finder->find($boolQuery);


        dump($results);

        dump('end');
        exit;
        $index = $this->get('fos_elastica.index.randomovies');
        $result = $index->search("big");
        dump($result);

        $finder = $this->get('fos_elastica.finder.randomovies.movie');
        $boolQuery = new BoolQuery();
        $fieldTitle = new Match();
        $fieldTitle->setField('title', 'temps');
        $boolQuery->addShould($fieldTitle);
        $data = $finder->find($boolQuery);
        dump($data);

        $query_part = new BoolQuery();
        $query_part->addShould(
            new Term([
                'title' => [
                    'value' => 'temps',
                    'boost' => 3
                ]
            ])
        );
        $query_part->addShould(
            new Term([
                'synopsis' => [
                    'value' => 'temps'
                ]
            ])
        );
        $filters = new BoolQuery();
        $filters->addMust(
            new Term([
                'language' => 'fr'
            ])
        );
//        $filters->addMust(
//            new \Elastica\Filter\NumericRange('published_at', array(
//                'lte' => date('c'),
//            ))
//        );

//        $query = new Filtered($query_part, $filters);
//        $query = new Filtered($query_part);
        $result2 = $index->search($query_part, $filters);
        dump($result2);

        dump("finir de lire : http://www.afsy.fr/avent/2013/20-elasticsearch-dans-votre-Symfony2");

        exit;
    }
}
