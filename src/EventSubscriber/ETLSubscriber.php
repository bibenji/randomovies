<?php

namespace Randomovies\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Randomovies\Entity\Movie;
use Randomovies\ETL\ETL;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Randomovies\ETL\Transform;
use Randomovies\ETL\Load;
use Randomovies\ETL\Extract;
use Monolog\Logger;

class ETLSubscriber implements EventSubscriber
{   
    /**     
     * @var Logger
     */
    public $logger;
    
    /**
     * @var Load
     */
    public $load;
    
    /** 
     * @var ETL
     */
    public $etl;
    
    /**
     * @param LoggerInterface $logger
     * @param Registry $registry
     * @param Transform $transform
     * @param Load $load
     */
    public function __construct(LoggerInterface $logger, Registry $registry, Transform $transform, Load $load)
    {   
        $this->logger = $logger;
        $this->load = $load;        
        $this->etl = new ETL($logger, $registry, $transform, $load);
    }
    
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {        
        if ($args->getObject() instanceof Movie) {
            $this->index($args);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {        
        if ($args->getObject() instanceof Movie) {            
            $this->index($args);
        }
    }

    private function index(LifecycleEventArgs $args)
    {
        $extract = new Extract($args->getObjectManager());
        $this->etl->setExtract($extract);        
        $this->etl->launch('Movies', $args->getObject());
    }
    
    public function postRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        
        if ($object instanceof Movie) {
            try {
                $this->load->deleteMovie($object->getId());
            } catch (\Exception $e) {
                $this->logger->error('Impossible to unPopulate movie {id}', [
                        'id' => $object->getId(),
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
