<?php

namespace Randomovies\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Randomovies\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername('bibenji');
        $user->setPlainPassword('123');
        $user->setEmail('benjamin.billette@hotmail.fr');
        $user->addRole('ROLE_ADMIN');

        $password = $this->container->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
//        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}