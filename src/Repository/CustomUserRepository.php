<?php

namespace Randomovies\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class CustomUserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}