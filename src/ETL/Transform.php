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
            	'genre' => $movie->getGenre(),
            	'year' => $movie->getYear(),
            	'duration' => $movie->getDuration(),
            	'rating' => $movie->getRating(),
            	'director' => $movie->getDirector(),
            	'actors' => $movie->getActors(),
            	'synopsis' => $movie->getSynopsis(),
            ];
        }
        
        return $transformedMovies;
    }
    
    public function transformUsers(array $users): array
    {
    	$transformedUsers = [];
    	
    	foreach ($users as $user) {
    		/** @var User $user */
    		
    		$likes = [];
    		foreach ($user->getComments() as $comment) {
    			if ($comment->getNote() >= 4) {
    				$likes[] = $comment->getMovie()->getId();
    			}
    		}
    		
    		$transformedUsers[$user->getId()] = [
    			'id' => $user->getId(),
    			'username' => $user->getUsername(),
    			'likes' => $likes,    			
    		];
    	}    	    	
    	    	
    	return $transformedUsers;
    }
}
