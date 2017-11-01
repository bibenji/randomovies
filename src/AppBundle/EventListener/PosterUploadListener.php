<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Movie;

class PosterUploadListener
{
	public function __construct($postersDirectory)
	{
		$this->postersDirectory = $postersDirectory;
	}
	
	public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Movie) {
            return;
        }

        if ($fileName = $entity->getPoster()) {
            $entity->setPoster(new File($this->postersDirectory.'/'.$fileName));
        }
    }
}