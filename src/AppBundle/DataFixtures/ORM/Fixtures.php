<?php

// src/AppBundle/DataFixtures/ORM/Fixtures.php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $movie = new Movie();
        $movie->setActors('Leonardo DiCaprio, Ellen Page, Cillian Murphy, Ken Watanabe, Joseph Gordon-Levitt, Marion Cotillard, Tom Hardy');
        $movie->setDirector('Christopher Nolan');
        $movie->setDuration(148);
        $movie->setGenre('Science-fiction');
        $movie->setPoster('');
        $movie->setRating(5);
        $movie->setReview('');
        $movie->setSynopsis('Dans un futur proche, les États-Unis ont développé ce qui est appelé le « rêve partagé », une méthode permettant d\'influencer l\'inconscient d\'une victime pendant qu\'elle rêve, donc à partir de son subconscient. Des « extracteurs » s\'immiscent alors dans ce rêve, qu\'ils ont préalablement modelé et qu\'ils peuvent contrôler, afin d\'y voler des informations sensibles stockées dans le subconscient de la cible. C\'est dans cette nouvelle technique que se sont lancés Dominic Cobb et sa femme, Mal. Ensemble, ils ont exploré les possibilités de cette technique et l\'ont améliorée, leur permettant d\'emboîter les rêves les uns dans les autres, accentuant la confusion et donc diminuant la méfiance de la victime. Mais l\'implication du couple dans ce projet a été telle que Mal a un jour perdu le sens de la réalité ; pensant être en train de rêver, elle s\'est suicidée, croyant ainsi revenir à sa vision de la réalité. Soupçonné de son meurtre, Cobb est contraint de fuir les États-Unis et d\'abandonner leurs enfants à ses beaux-parents. Il se spécialise dans l\'« extraction », en particulier dans le domaine de l\'espionnage industriel ; mercenaire et voleur, il est embauché par des multinationales pour obtenir des informations de leurs concurrents commerciaux.');
        $movie->setTitle('Inception');
        $movie->setYear(2010);
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setActors('Johnny Depp, Amber Heard, Aaron Eckhart, Richard Jenkins');
        $movie->setDirector('Bruce Robinson');
        $movie->setDuration(110);
        $movie->setGenre('Comédie dramatique');
        $movie->setPoster('');
        $movie->setRating(5);
        $movie->setReview('');
        $movie->setSynopsis('Paul Kemp (Johnny Depp), journaliste gonzo, arrive à Porto Rico afin de collaborer au journal San Juan Daily News. Il y rencontre un riche entrepreneur, Sanderson (Aaron Eckhart), qui lui fera part d\'un projet immobilier secret…');
        $movie->setTitle('Rhum express');
        $movie->setYear(2011);
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setActors('Jeff Bridges, John Goodman, Julianne Moore, Steve Buscemi');
        $movie->setDirector('Joel Coen');
        $movie->setDuration(117);
        $movie->setGenre('Comédie');
        $movie->setPoster('');
        $movie->setRating(5);
        $movie->setReview('');
        $movie->setSynopsis('Présenté par un narrateur (Sam Elliott) comme un fainéant vivant dans le comté de Los Angeles, Jeffrey Lebowski, dit « le Duc » (Jeff Bridges), passe son temps à jouer au bowling avec ses amis Walter Sobchak (John Goodman) et Donny (Steve Buscemi). Un soir, en rentrant chez lui, il est attendu par deux voyous qui le somment de rendre l\'argent que sa femme doit à Jackie Treehorn, et l\'un d\'eux urine sur son tapis juste avant qu\'ils ne se rendent compte qu\'ils ont fait erreur sur la personne, l\'ayant confondu avec un autre Jeffrey Lebowski. Poussé par son ami Walter, le Duc se rend chez son homonyme, un millionnaire paraplégique (David Huddleston), afin d\'obtenir une compensation pour son tapis, qui lui est refusée. Le Duc quitte alors la résidence du millionnaire en volant un tapis, et rencontre Bunny Lebowski (Tara Reid), sa jeune épouse nymphomane.');
        $movie->setTitle('The Big Lebowski');
        $movie->setYear(1998);
        $manager->persist($movie);

        $manager->flush();
    }
}