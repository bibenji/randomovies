<?php

namespace Randomovies\ETL;

use Elasticsearch\ClientBuilder;

class Client
{
    /**
     * @var \Elasticsearch\Client
     */
    private $client;

    /**
     * @var ElasticsearchLogger
     */
    private $logger;

    /**
     * @var mixed
     */
    private $indexes;

    /**
     * Client constructor.
     * @param array $config
     * @param ElasticsearchLogger $logger
     */
    public function __construct(array $config, ElasticsearchLogger $logger)
    {    	
        $this->indexes = $config['indexes'];
        $this->logger = $logger;
        $this->client = ClientBuilder::create()
                            ->setHosts($config['hosts'])
                            ->setLogger($logger)
                            ->build();
    }
    
    public function getIndex(): string
    {
        return $this->getIndexForMovies();
    }
    
    public function getIndexForMovies(): string
    {
    	return $this->indexes['movies'];    	
    }
    
    public function getIndexForUsers(): string
    {
    	return $this->indexes['users'];
    }
    
    public function index($params): array
    {
        $data = $this->client->index($params);
        $this->logRequestInfo();
        return $data;
    }

    public function delete($params): array
    {
        $data = $this->client->delete($params);
        $this->logRequestInfo();
        return $data;
    }

    public function bulk($params): array
    {    	
        $data = $this->client->bulk($params);        
        $this->logRequestInfo();
        return $data;
    }

    public function search($params): array
    {
        $data = $this->client->search($params);
        $this->logRequestInfo();
        return $data;
    }

    public function logRequestInfo()
    {
        $info = $this->client->transport->getConnection()->getLastRequestInfo();		        
        
        $this->logger->logQuery(
            $info['request']['uri'],
            $info['request']['http_method'],
            $info['request']['body'],
//         	undefined index: transfer_stats
//             $info['request']['transfer_stats']['total_time'],
			0,
            [
                'method' => $info['request']['scheme'],
                'transport' => $info['request']['scheme'],
                'host' => explode(':', $this->client->transport->getConnection()->getHost())[0],
                'port' => '',
            ]
        );
    }
}