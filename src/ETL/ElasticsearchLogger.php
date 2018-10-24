<?php

namespace Randomovies\ETL;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class ElasticsearchLogger extends AbstractLogger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $queries = [];

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var array
     */
    protected $queryLogs = [];

    /**
     * ElasticsearchLogger constructor.
     * @param LoggerInterface|null $logger
     * @param bool $debug
     */
    public function __construct(LoggerInterface $logger = null, $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * @param $path
     * @param $method
     * @param $data
     * @param $queryTime
     * @param array $connection
     * @param array $query
     * @param int $engineTime
     * @param int $itemCount
     */
    public function logQuery($path, $method, $data, $queryTime, $connection = [], $query = [], $engineTime = 0, $itemCount = 0)
    {
        $executionMS = $queryTime * 1000;

        if ($this->debug) {
            $e = new \Exception();
            if (is_string($data)) {
                $jsonStrings = explode("\n", $data);
                $data = [];
                foreach ($jsonStrings as $json) {
                    if ($json != '') {
                        $data[] = json_decode($json, true);
                    }
                }
            } else {
                $data = [$data];
            }

            $this->queries[] = [
                'path' => $path,
                'method' => $method,
                'data' => $data,
                'executionMS' => $executionMS,
                'engineMS' => $engineTime,
                'connection' => $connection,
                'queryString' => $query,
                'itemCount' => $itemCount,
                'backtrace' => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * @return int
     */
    public function getNbQueries()
    {
        return count($this->queries);
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    public function log($level, $message, array $context = [])
    {
        return $this->logger->log($level, $message, $context);
    }

    public function reset()
    {
        $this->queries = [];
    }
}