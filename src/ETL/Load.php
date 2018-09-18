<?php

namespace Randomovies\ETL;

class Load
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    private $type;

    /**
     * Load constructor.
     * @param Client $client
     * @param $type
     */
    public function __construct(Client $client, $type)
    {
        $this->client;
        $this->type = $type;
    }

    /**
     * @param array $movie
     */
    public function loadMovie(array $movie)
    {
        $id = $movie['id'];
        unset($movie['id']);
        $params = [
            'index' => $this->client->getIndex(),
            'type' => $this->type,
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
            'index' => $this->client->getIndex(),
            'type' => $this->type,
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
                        '_index' => $this->client->getIndex(),
                        '_type' => $this->type,
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
            'index' => $this->client->getIndex(),
            'type' => $this->type,
            'body' => $params,
        ]);
    }
}
