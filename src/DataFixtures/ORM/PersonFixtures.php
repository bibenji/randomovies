<?php

namespace Randomovies\DataFixtures\ORM;

use Randomovies\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{
    const PEOPLE = [
        'Leonardo DiCaprio' => [
            'firstname' => 'Leonardo',
            'lastname' => 'DiCaprio',
            'birthdate' => '1974-11-11',
            'gender' => 'M'
        ],
        'Joseph Gordon-Levitt' => [
            'firstname' => 'Joseph',
            'lastname' => 'Gordon-Levitt',
            'birthdate' => '1981-02-17',
            'gender' => 'M'
        ],
        'Cillian Murphy' => [
            'firstname' => 'Cillian',
            'lastname' => 'Murphy',
            'birthdate' => '1976-05-25',
            'gender' => 'M'
        ],
        'Johnny Depp' => [
            'firstname' => 'Johnny',
            'lastname' => 'Depp',
            'birthdate' => '1963-06-09',
            'gender' => 'M'
        ],
        'Amber Heard' => [
            'firstname' => 'Amber',
            'lastname' => 'Heard',
            'birthdate' => '1986-04-22',
            'gender' => 'W'
        ],
        'Jeff Bridges' => [
            'firstname' => 'Jeff',
            'lastname' => 'Bridges',
            'birthdate' => '1949-12-04',
            'gender' => 'M'
        ],
        'John Goodman' => [
            'firstname' => 'John',
            'lastname' => 'Goodman',
            'birthdate' => '1952-06-20',
            'gender' => 'M'
        ],
        'Julianne Moore' => [
            'firstname' => 'Julianne',
            'lastname' => 'Moore',
            'birthdate' => '1960-12-03',
            'gender' => 'W'
        ],
        'Edward Norton' => [
            'firstname' => 'Edward',
            'lastname' => 'Norton',
            'birthdate' => '1969-08-18',
            'gender' => 'M'
        ],
        'Brad Pitt' => [
            'firstname' => 'Brad',
            'lastname' => 'Pitt',
            'birthdate' => '1963-12-18',
            'gender' => 'M'
        ],
        'Helena Bonham Carter' => [
            'firstname' => 'Helena',
            'lastname' => 'Bonham Carter',
            'birthdate' => '1966-05-26',
            'gender' => 'W'
        ],
//        '' => [
//            'firstname' => '',
//            'lastname' => '',
//            'birthdate' => '',
//            'gender' => ''
//        ],
    ];

    public function load(ObjectManager $manager)
    {
//         foreach (self::PEOPLE as $index => $one) {
//             $person = new Person();
//             $birthdate = new \DateTime($one['birthdate']);
//             $person->setBirthdate($birthdate);
//             $person->setFirstname($one['lastname']);
//             $person->setLastname($one['firstname']);
//             $person->setGender($one['gender']);
//             $this->addReference($index, $person);
//             $manager->persist($person);
//         }

//         $manager->flush();
    }
}