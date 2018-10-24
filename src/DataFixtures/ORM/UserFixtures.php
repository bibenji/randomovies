<?php

namespace Randomovies\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Randomovies\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory as FakerFactory;
use Randomovies\Entity\Comment;
use Randomovies\Entity\Review;

class UserFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {    	
    	$faker = FakerFactory::create();
    	
        $bibenji = new User();
        $bibenji->setUsername('bibenji');
        $bibenji->setPlainPassword('123');
        $bibenji->setEmail('benjamin.billette@hotmail.fr');
        $bibenji->addRole('ROLE_ADMIN');        
        $password = $this->container->get('security.password_encoder')->encodePassword($bibenji, $bibenji->getPlainPassword());
// 		$password = $this->encoder->encodePassword($bibenji, $bibenji->getPlainPassword());
        $bibenji->setPassword($password);
        
        $j = 0;
        while ($j <= 20) {
        	$j++;
        	$newReview = new Review();
        	$newReview->setRating(3);
        	$newReview->setReview($faker->paragraph(5, FALSE));
        	$newReview->setMovie($this->getReference('movie'.$j));
        	$newReview->setMain(TRUE);
        	$bibenji->addReview($newReview);
        }
        
        $manager->persist($bibenji);
        
        $sisimon = new User();        
        $sisimon->setUsername('sisimon');
        $sisimon->setPlainPassword('123');
        $sisimon->setEmail('simon.thomas@hotmail.fr');
        $sisimon->addRole('ROLE_CONTRIBUTOR');
        $password = $this->container->get('security.password_encoder')->encodePassword($sisimon, $sisimon->getPlainPassword());
// 		$password = $this->encoder->encodePassword($sisimon, $sisimon->getPlainPassword());
        $sisimon->setPassword($password);        
        $manager->persist($sisimon);
                
        $i = 0;
        while ($i <= 20) {
        	$i++;
        	$newUser = new User();
        	$newUser->setPlainPassword('123');
        	$firstname = $faker->firstname;
        	$lastname = $faker->lastname;
        	$newUser->setUsername('user'.$i);
        	$newUser->setEmail($firstname.'.'.$lastname.'@hotmail.fr');
        	$newUser->addRole('ROLE_USER');
        	$password = $this->container->get('security.password_encoder')->encodePassword($newUser, $newUser->getPlainPassword());
// 			$password = $this->encoder->encodePassword($sisimon, $sisimon->getPlainPassword());
        	$newUser->setPassword($password);
        	
        	$j = 0;
        	while ($j <= 20) {
        		$j++;
        		$newComment = new Comment();
        		$newComment->setNote($faker->numberBetween(1, 5));
        		$newComment->setComment($faker->paragraph(3, FALSE));        		
        		$newComment->setMovie($this->getReference('movie'.$j));        		
        		$newUser->addComment($newComment);
        	}
        	
        	$manager->persist($newUser);
        }
        
        $manager->flush();
    }
    
    public function getDependencies()
    {
    	return array(
    		MovieFixtures::class,
    	);
    }
}
