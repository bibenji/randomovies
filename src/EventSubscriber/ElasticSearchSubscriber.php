<?php

namespace Randomovies\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Randomovies\Entity\Movie;
use Randomovies\ETL\ETL;
use Randomovies\ETL\Load;

class ElasticSearchSubscriber implements EventSubscriber
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Load
     */
    protected $load;

    /**
     * @var ETL
     */
    protected $etl;

    public function __construct(LoggerInterface $logger, Load $load, ETL $etl)
    {
        $this->logger = $logger;
        $this->load = $load;
        $this->etl = $etl;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'postPersist',
            'postUpdate',
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Movie) {
            try {
                $this->load->deleteMovie($object->getId());
            } catch (\Exception $e) {
                $this->logger->error('Impossible to unPopulate movie {id}', [
                    'id' => $object->getId(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->postUpdate($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Movie) {
            $this->etl->launch($object->getId());
        }
    }
}
