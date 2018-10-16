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
    
    public function launch($index = NULL, $id = NULL)
    {	   	    	
    	if (in_array($index, ['Movies','Users'])) {
    		$this->handleLaunch([$index], $id);    		
    	} else {
    		$this->handleLaunch(['Movies', 'Users'], NULL);
    	}
    }

    private function handleLaunch(array $indexes, $id = NULL)
    {    	
        try {
        	
        	foreach ($indexes as $index) {        		       		
        		
        		$getFunction = 'get'.$index;
        		$transformFunction = 'transform'.$index;
        		$loadFunction = 'load'.$index;
        		$maxIdFunction = 'getMax'.$index.'Id';        		        		
        		        		
	            if ($id) {
	                $entity = $this->extract->{rtrim($getFunction, 's')}($id, $id);	                
	                $transformedEntities = $this->transform->{$transformFunction}([$entity]);	                
	                $this->load->{$loadFunction}($transformedEntities);
	                return;
	            } elseif ($this->extract->{$maxIdFunction}() < $this->batchSize) {
	                $maxId = $this->batchSize;
	            } else {
	                $maxId = $this->extract->{$maxIdFunction}();
	            }
		            
	            for ($BatchMax = $this->batchSize, $i = 0; $BatchMax <= $maxId + $this->batchSize; $BatchMax += $this->batchSize, $i++) {	
	                $idBatchMax = ($BatchMax < $maxId) ? $BatchMax : $maxId;
	                $idBatchMin = $i * $this->batchSize;
	                
	                $entities = $this->extract->{$getFunction}($idBatchMin, $idBatchMax);
	                
	                $transformedEntities = $this->transform->{$transformFunction}($entities);	                          
	                
	                if (count($transformedEntities) > 0) {
	                	$this->load->{$loadFunction}($transformedEntities);
	                }
	
	                $this->registry->getManager()->clear();	                
	            }
            
        	}
        } catch (\Exception $e) {
            $msg = 'Une erreur est survenue durant la mise à jour de l\'index ElasticSearch';
            $this->logger->error($msg, ['code' => $e->getCode(), 'message' => $e->getMessage()]);
            return;
        }
    }
}