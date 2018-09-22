<?php

namespace Randomovies\ETL;

use Doctrine\ORM\EntityManager;
use Randomovies\Entity\Movie;

class Extract
{
    protected $movieRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->movieRepository = $entityManager->getRepository(Movie::class);
    }

    public function getMovies($min, $max): array
    {
        return $this->movieRepository->getMoviesForETL($min, $max);
    }

    public function getMaxId()
    {
        return $this->movieRepository->getTotalMovies();
    }
}
