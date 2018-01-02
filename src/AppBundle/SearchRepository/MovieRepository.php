<?php

namespace AppBundle\SearchRepository;

use AppBundle\Entity\MovieSearch;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Repository;

class MovieRepository extends Repository
{
    /**
     * @param MovieSearch $movieSearch
     * @return array
     */
    public function findMoviesWithSearchData(MovieSearch $movieSearch)
    {
        $boolQuery = new BoolQuery();

//        if (null !== $movieSearch->getTitle()) {
            $fieldTitle = new Match();
            $fieldTitle->setField(
                'title',
                $movieSearch->getTitle() ?? ""
            );
            $boolQuery->addShould($fieldTitle);
//        }

//        if (null !== $movieSearch->getSynopsis()) {
            $fieldSynopsis = new Match();
            $fieldSynopsis->setField(
                'synopsis',
                $movieSearch->getSynopsis() ?? ""
            );
            $boolQuery->addShould($fieldSynopsis);
//        }

        if (null !== $movieSearch->getGenre() && "" !== $movieSearch->getGenre()) {
            $genreQuery = new Match();
            $genreQuery->setField(
                'genre',
                $movieSearch->getGenre()
            );
            $boolQuery->addMust($genreQuery);
        }

        $data = $this->find($boolQuery);
        return $data;
    }
}
