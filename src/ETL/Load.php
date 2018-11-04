<?php

namespace Randomovies\ETL;

class Load
{
	const MOVIE_TYPE = 'movie';
	const USER_TYPE = 'user';
	
    /**
     * @var Client
     */
    protected $client;

    /**
     * Load constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $movie
     */
    public function loadMovie(array $movie)
    {
        $id = $movie['id'];
        unset($movie['id']);
        $params = [
            'index' => $this->client->getIndexForMovies(),
            'type' => self::MOVIE_TYPE,
            'id' => $id,
            'body' => $movie,
        ];

        $this->client->index($params);
    }

    /**
     * @param $movieId
     */
    public function deleteMovie($movieId)
    {
        $params = [
            'index' => $this->client->getIndexForMovies(),
            'type' => self::MOVIE_TYPE,
            'id' => $movieId,
        ];

        $this->client->delete($params);
    }

    /**
     * @param array $movies
     */
    public function loadMovies(array $movies)
    {
        $params = [];

        if (1 === count($movies)) {
            $this->loadMovie(array_pop($movies));
        } else {
            foreach ($movies as $movie) {
                $id = $movie['id'];
                unset($movie['id']);

                if (!isset($params['body'])) {
                    $params['body'] = [];
                }

                $params['body'][] = [
                    'index' => [
                        '_index' => $this->client->getIndexForMovies(),
                        '_type' => self::MOVIE_TYPE,
                        '_id' => $id,
                    ],
                ];

                $params['body'][] = $movie;
            }
            
            $this->client->bulk($params);
        }
    }

    public function searchMovieWithParams($params)
    {
        return $this->client->search([
            'index' => $this->client->getIndexForMovies(),
            'type' => self::MOVIE_TYPE,
            'body' => $params,
        ]);
    }
    
    /**
     * @param array $user
     */
    public function loadUser(array $user)
    {
    	$id = $user['id'];
    	unset($user['id']);
    	$params = [
			'index' => $this->client->getIndexForUsers(),
			'type' => self::USER_TYPE,
			'id' => $id,
			'body' => $user,
    	];    	
    	$this->client->index($params);    	
    }
    
    /**
     * @param $userId
     */
    public function deleteUser($userId)
    {
    	$params = [
			'index' => $this->client->getIndexForUsers(),
			'type' => self::USER_TYPE,
			'id' => $userType,
    	];
    	
    	$this->client->delete($params);
    }
    
    /**
     * @param array $users
     */
    public function loadUsers(array $users)
    {
    	$params = [];
    	
    	if (1 === count($users)) {
    		$this->loadUser(array_pop($users));
    	} else {
    		foreach ($users as $user) {
    			$id = $user['id'];
    			unset($user['id']);
    			
    			if (!isset($params['body'])) {
    				$params['body'] = [];
    			}
    			
    			$params['body'][] = [
					'index' => [
						'_index' => $this->client->getIndexForUsers(),
						'_type' => self::USER_TYPE,
						'_id' => $id,
					],
    			];
    			
    			$params['body'][] = $user;
    		}
    		
    		$this->client->bulk($params);
    	}
    }
    
    public function searchUserWithParams($params)
    {
    	return $this->client->search([
			'index' => $this->client->getIndexForUsers(),
			'type' => self::USER_TYPE,
			'body' => $params,
    	]);
    }
}
