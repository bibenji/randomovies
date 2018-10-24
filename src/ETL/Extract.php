<?php

namespace Randomovies\ETL;

use Doctrine\ORM\EntityManager;
use Randomovies\Entity\Movie;
use Randomovies\Entity\User;

class Extract
{
    protected $movieRepository;
    protected $userRepository;

    public function __construct(EntityManager $entityManager)    
    {
        $this->movieRepository = $entityManager->getRepository(Movie::class);
        $this->userRepository = $entityManager->getRepository(User::class);
    }
	
    public function getMovie($id)
    {
    	return $this->movieRepository->find($id);	
    }
    
    public function getMovies($min, $max): array
    {
        return $this->movieRepository->getMoviesForETL($min, $max);
    }

    /**
     * With s for reusability in ETL.php
     * 
     * @return unknown
     */
    public function getMaxMoviesId()
    {
    	return $this->movieRepository->getMaxMoviesId();
    }
    
    public function getUser($id)
    {
    	return $this->userRepository->find($id);
    }
    
    public function getUsers($min, $max): array
    {
    	return $this->userRepository->getUsersForETL($min, $max);
    }
    
    /**
     * With s for reusability in ETL.php
     * 
     * @return unknown
     */
    public function getMaxUsersId()
    {
    	return $this->userRepository->getMaxUsersId();
    }
}
