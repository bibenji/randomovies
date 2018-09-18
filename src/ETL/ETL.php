<?php

namespace Randomovies\ETL;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;

class ETL
{
    /**
     * @var Extract
     */
    protected $extract;

    /**
     * @var Transform
     */
    protected $transform;

    /**
     * @var Load
     */
    protected $load;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var int
     */
    protected $batchSize;

    /**
     * ETL constructor.
     * @param Extract $extract
     * @param Transform $transform
     * @param Load $load
     * @param Registry $registry
     * @param Logger $logger
     */
    public function __construct(Extract $extract, Transform $transform, Load $load, Registry $registry, Logger $logger)
    {
        $this->extract = $extract;
        $this->transform = $transform;
        $this->load = $load;
        $this->registry = $registry;
        $this->logger = $logger;
        $this->batchSize = 500;
    }

    public function launch($id = null)
    {
        try {
            if ($id) {
                $movies = $this->extract->getMovies($id, $id); // @todo : voir c'est quoi ce double id
                $transformedMovies = $this->transform->transformMovies($movies);
                $this->load->loadMovies($transformedMovies);
                return;
            } elseif ($this->extract->getMaxId() < $this->batchSize) {
                $maxId = $this->batchSize;
            } else {
                $maxId = $this->extract->getMaxId();
            }

            for ($BatchMax = $this->batchSize, $i = 0; $BatchMax <= $maxId + $this->batchSize; $BatchMax += $this->batchSize, $i++) {

                $idBatchMax = ($BatchMax < $maxId) ? $BatchMax : $maxId;
                $idBatchMin = $i * $this->batchSize;

                $movies = $this->extract->getMovies($idBatchMin, $idBatchMax);

                $transformedMovies = $this->transform->transformMovies($movies);

                if (count($transformedMovies) > 0) {
                    $this->load->loadMovies($transformedMovies);
                }

                $this->registry->getManager()->clear();

            }
        } catch (\Exception $e) {
            $msg = 'Une erreur est survenue durant la mise à jour de l\'index ElasticSearch';
            $this->logger->error($msg, ['code' => $e->getCode(), 'message' => $e->getMessage()]);
            return;
        }
    }
}