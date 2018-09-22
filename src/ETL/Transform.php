<?php

namespace Randomovies\ETL;

use Randomovies\Entity\Movie;

class Transform
{
    public function transformMovies(array $movies): array
    {
        $transformedMovies = [];

        foreach ($movies as $movie) {
            /** @var Movie $movie */
            $transformedMovies[$movie->getId()] = [
                'id' => $movie->getId(),
                'title' => $movie->getTitle(),
                // @todo : à compléter
            ];
        }
    }
}
