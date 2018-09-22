<?php

namespace Randomovies\ETL;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ElasticsearchDataCollector extends DataCollector
{
    /**
     * @var ElasticsearchLogger
     */
    protected $logger;

    /**
     * ElasticsearchDataCollector constructor.
     * @param ElasticsearchLogger $logger
     */
    public function __construct(ElasticsearchLogger $logger)
    {
        $this->logger = $logger;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['nb_queries'] = $this->logger->getNbQueries();
        $this->data['queries'] = $this->logger->getQueries();
    }

    /**
     * @return mixed
     */
    public function getQueryCount()
    {
        return $this->data['nb_queries'];
    }

    /**
     * @return mixed
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @return int
     */
    public function getTime()
    {
        $time = 0;
        foreach ($this->data['queries'] as $query) {
            $time += $query['engineMS'];
        }

        return $time;
    }

    /**
     * @return int
     */
    public function getExecutionTime()
    {
        $time = 0;
        foreach ($this->data['queries'] as $query) {
            $time += $query['executionMS'];
        }

        return $time;
    }

    public function getName()
    {
        return 'app.elasticsearch_data_collector';
    }

    public function reset()
    {
        $this->data = [];
    }
}
