<?php

namespace Randomovies\EventListener;

use Randomovies\Entity\Person;
use Randomovies\Entity\Role;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Randomovies\Entity\Movie;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Randomovies\Tool\ImageResizer;

class MovieListener
{
    /**
     * @var Movie
     */
    private $entity;
	
	/**
	 * @var string
	 */
	private $postersDirectory;

    public function __construct(string $postersDirectory)
    {
		$this->postersDirectory = $postersDirectory;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createDirectorAndActors($args);
    }
	
	public function postPersist(LifecycleEventArgs $args)
    {
		$this->createThumbnails($args);        
    }
	
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->createDirectorAndActors($args);		
    }
	
	public function postUpdate(LifecycleEventArgs $args)
    {		
		$changeArray = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($args->getObject());
		
		if (isset($changeArray['poster'])) {			
			$this->createThumbnails($args);
		}		
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (null !== $this->entity) {
            $entityManager = $args->getEntityManager();
            $entityManager->persist($this->entity);
            $entityManager->flush();
        }
    }

    private function createDirectorAndActors(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
//        $entityManager = $args->getEntityManager();

        if (!$entity instanceof Movie) {
            return;
        }

        if (null !== $entity->getDirector()) {
            $explodedDirectorFullName = explode(' ', $entity->getDirector());

            if (count($explodedDirectorFullName) >= 2) {
                $em = $args->getEntityManager();
                $personRepository = $em->getRepository('Randomovies:Person');
                $person = $personRepository->findOneBy([
                    'firstname' => $explodedDirectorFullName[0],
                    'lastname' => $explodedDirectorFullName[1]
                ]);

                if (null === $person) {
                    $person = new Person();
                    $person->setFirstname($explodedDirectorFullName[0]);
                    $person->setLastname($explodedDirectorFullName[1]);
                }

                $role = new Role();
                $role->setPerson($person);
                $role->setMovie($entity);
                $role->setRole(Role::ROLE_REALISATOR);

                $entity->addRole($role);
            }
        }

        if (null !== $entity->getActors()) {
            $explodedActors = explode(', ', $entity->getActors());

            foreach ($explodedActors as $actor) {
                $explodedActorFullName = explode(' ', $actor);

                if (count($explodedActorFullName) >= 2) {
                    $em = $args->getEntityManager();
                    $personRepository = $em->getRepository('Randomovies:Person');
                    $person = $personRepository->findOneBy([
                        'firstname' => $explodedActorFullName[0],
                        'lastname' => $explodedActorFullName[1]
                    ]);

                    if (null === $person) {
                        $person = new Person();
                        $person->setFirstname($explodedActorFullName[0]);
                        $person->setLastname($explodedActorFullName[1]);
                    }

                    $role = new Role();
                    $role->setPerson($person);
                    $role->setMovie($entity);
                    $role->setRole(Role::ROLE_ACTOR);

                    $entity->addRole($role);
                }
            }
        }

        $this->entity = $entity;
    }
	
	private function createThumbnails($args)
	{
		$entity = $args->getEntity();
		
        if (!$entity instanceof Movie) {
            return;
        }
        
        if (NULL !== $entity->getPoster()) {
        	$imageResizer = new ImageResizer();
        	$imageResizer->makeSmallAndMediumThumbnails($this->postersDirectory, $entity->getPoster());
        }		
	}
}