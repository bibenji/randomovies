<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Movie;
use Doctrine\ORM\Event\PostFlushEventArgs;

class MoviePersistListener
{
    /**
     * @var Movie
     */
    private $entity;

    public function __construct()
    {

    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createDirectorAndActors($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->createDirectorAndActors($args);
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
                $personRepository = $em->getRepository('AppBundle:Person');
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
                    $personRepository = $em->getRepository('AppBundle:Person');
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
}