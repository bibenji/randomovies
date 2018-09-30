<?php

namespace Randomovies\Dto\Mapper;

use Randomovies\Dto\MovieDto;
use Randomovies\Entity\Movie;

class MovieMapper extends Mapper
{
    /**
     * @param MovieDto $movieDto
     * @param Movie $movie
     */
    public function map($movieDto, $movie)
    {
        parent::map($movieDto, $movie);
    }
}
