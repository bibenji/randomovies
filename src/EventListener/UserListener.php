<?php

namespace Randomovies\EventListener;

use Randomovies\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Randomovies\Tool\ImageResizer;

class UserListener
{
	/**
	 * @var string
	 */
	private $usersPhotosDirectory;

    public function __construct(string $usersPhotosDirectory)
    {
		$this->usersPhotosDirectory = $usersPhotosDirectory;
    }

	public function postUpdate(LifecycleEventArgs $args)
    {		
		$changeArray = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($args->getObject());
		
		if (isset($changeArray['photo'])) {			
			$this->createThumbnails($args);
		}		
    }

	private function createThumbnails($args)
	{		
		$entity = $args->getEntity();
		
        if (!$entity instanceof User) {
            return;
        }
		
		$imageResizer = new ImageResizer();
		$imageResizer->makeSmallThumbnail($this->usersPhotosDirectory, $entity->getPhoto(), 400, 400);		
		$imageResizer->makeMediumThumbnail($this->usersPhotosDirectory, $entity->getPhoto(), 800, 800);		
	}
}
