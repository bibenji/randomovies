<?php

namespace Randomovies\DataFixtures\ORM;

use Randomovies\Entity\Movie;
use Randomovies\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory as FakerFactory;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
	const GENRES = ['Comédie','Comédie dramatique','Thriller','Science-fiction','Action','Drame'];
	
    const MOVIES = [
        'Inception' => [
            'actors' => 'Leonardo DiCaprio, Ellen Page, Cillian Murphy, Ken Watanabe, Joseph Gordon-Levitt, Marion Cotillard, Tom Hardy',
            'people' => ['Leonardo DiCaprio', 'Cillian Murphy', 'Joseph Gordon-Levitt'],
            'director' => 'Christopher Nolan',
            'duration' => 148,
            'genre' => 'Science-fiction',
            'poster' => 'inception-2.jpg',
            'rating' => 5,
            'review' => '',
            'synopsis' => 'Dans un futur proche, les États-Unis ont développé ce qui est appelé le « rêve partagé », une méthode permettant d\'influencer l\'inconscient d\'une victime pendant qu\'elle rêve, donc à partir de son subconscient. Des « extracteurs » s\'immiscent alors dans ce rêve, qu\'ils ont préalablement modelé et qu\'ils peuvent contrôler, afin d\'y voler des informations sensibles stockées dans le subconscient de la cible. C\'est dans cette nouvelle technique que se sont lancés Dominic Cobb et sa femme, Mal. Ensemble, ils ont exploré les possibilités de cette technique et l\'ont améliorée, leur permettant d\'emboîter les rêves les uns dans les autres, accentuant la confusion et donc diminuant la méfiance de la victime. Mais l\'implication du couple dans ce projet a été telle que Mal a un jour perdu le sens de la réalité ; pensant être en train de rêver, elle s\'est suicidée, croyant ainsi revenir à sa vision de la réalité. Soupçonné de son meurtre, Cobb est contraint de fuir les États-Unis et d\'abandonner leurs enfants à ses beaux-parents. Il se spécialise dans l\'« extraction », en particulier dans le domaine de l\'espionnage industriel ; mercenaire et voleur, il est embauché par des multinationales pour obtenir des informations de leurs concurrents commerciaux.',
            'title' => 'Inception',
            'year' =>  2010
        ],
        'Le Temps des Gitans' => [
            'actors' => 'Davor Dujmović, Bora Todorović, Ljubica Adzovic',
            'director' => 'Emir Kusturica',
            'duration' => 132,
            'genre' => 'Drame',
            'poster' => 'le-temps-des-gitans.jpg',
            'rating' => 5,
            'review' => '',
            'synopsis' => 'Perhan est un rom. Fils d\'un soldat slovène et d\'une rom, il est élevé ainsi que sa sœur handicapée Danira par sa grand-mère dans un bidonville de Skopje en Yougoslavie (devenue la Macédoine en 1991). La vie de famille s\'organise autour d\'un accordéon, d\'un dindon et d\'un oncle déluré. Perhan tombe amoureux d\'Azra, la fille de la voisine, mais la mère refuse de donner sa fille en mariage car Perhan est pauvre et n\'a pas de situation.',
            'title' => 'Le Temps des Gitans',
            'year' =>  1989
        ],
        'Rhum express' => [
            'actors' => 'Johnny Depp, Amber Heard, Aaron Eckhart, Richard Jenkins',
            'people' => ['Johnny Depp', 'Amber Heard'],
            'director' => 'Bruce Robinson',
            'duration' => 110,
            'genre' => 'Comédie dramatique',
            'poster' => 'the-rum-diary.jpg',
            'rating' => 4,
            'review' => '',
            'synopsis' => 'Paul Kemp (Johnny Depp), journaliste gonzo, arrive à Porto Rico afin de collaborer au journal San Juan Daily News. Il y rencontre un riche entrepreneur, Sanderson (Aaron Eckhart), qui lui fera part d\'un projet immobilier secret…',
            'title' => 'Rhum express',
            'year' =>  2011
        ],
        'The Big Lebowski' => [
            'actors' => 'Jeff Bridges, John Goodman, Julianne Moore, Steve Buscemi',
            'people' => ['Jeff Bridges', 'John Goodman', 'Julianne Moore'],
            'director' => 'Joel Coen',
            'duration' => 117,
            'genre' => 'Comédie',
            'poster' => 'the-big-lebowski.jpeg',
            'rating' => 5,
            'review' => '',
            'synopsis' => 'The Big Lebowski ou Erreur sur la personne au Québec, est un film américano-britannique, sorti en 1998, réalisé par Joel Coen et écrit en collaboration avec son frère Ethan. Dans cette comédie pastichant le film noir, Jeff Bridges incarne le personnage de Jeffrey Lebowski, un fainéant sans emploi et grand amateur de bowling qui préfère se faire appeler « le Duc » (The Dude en version originale). À la suite d\'une confusion d\'identité, le Duc fait la connaissance d\'un millionnaire également appelé Jeffrey Lebowski et, lorsque la jeune épouse du millionnaire est enlevée, celui-ci fait appel au Duc pour apporter la rançon demandée par ses ravisseurs. Mais les choses commencent à aller de travers quand Walter Sobchak, le meilleur ami du Duc (interprété par John Goodman), projette de garder la rançon pour eux. ',
            'title' => 'The Big Lebowski',
            'year' =>  1998
        ],
        'The Fountain' => [
            'actors' => 'Hugh Jackman, Rachel Weisz, Ellen Burstyn',
            'director' => 'Darren Aronofsky',
            'duration' => 96,
            'genre' => 'Science-fiction',
            'poster' => 'the-fountain.jpg',
            'rating' => 4,
            'review' => '',
            'synopsis' => 'The Fountain est une odyssée sur le combat millénaire d\'un homme pour sauver la femme qu\'il aime, mais aussi son voyage initiatique dans l\'acceptation de la mort.<br />Il s\'agit aussi d\'un combat intérieur afin d\'atteindre un état pur, de liberté, de plénitude et d\'amour.<br />Le film relate trois récits entrelacés qui se déroulent dans le cadre de l\'Espagne des conquistadores, le présent et une dimension spirituelle que vit le personnage principal lorsqu\'il est en transe méditative.<br />Ces trois récits entrelacés peuvent être perçus comme un seul récit qui comporte trois réalités. Il s\'agirait alors du récit d\'un homme à travers le temps et à travers son évolution intérieure et spirituelle.',
            'title' => 'The Fountain',
            'year' =>  2006
        ],
        'Showgirls' => [
            'actors' => 'Elizabeth Berkley, Kyle MacLachlan, Gina Gershon',
            'director' => 'Paul Verhoeven',
            'duration' => 131,
            'genre' => 'Drame',
            'poster' => 'showgirls.jpeg',
            'rating' => 3,
            'review' => '',
            'synopsis' => 'La jeune et belle Nomi Malone se rend à Las Vegas dans l\'espoir de faire carrière en tant que show girl. Un dénommé Jeff, qui l\'a prise en auto-stop, en profite pour lui voler tout son argent. Nomi rencontre Molly Abrams, une costumière et créatrice de costumes qui la prend comme colocataire. Molly invite Nomi dans les coulisses du spectacle Goddess donné au Casino Stardust où elle travaille. Molly la présente à Cristal Connors, la vedette principale de la revue seins-nus. Quand Nomi dit à Cristal qu\'elle danse au Club Topless Cheetah, Cristal se moque d\'elle et lui dit qu\'elle se prostitue. Nomi étant trop perturbée pour aller travailler ce soir-là, Molly l\'emmène danser au Holbank Pinks Club, où son ami James Smith travaille en tant que videur. James demande à Nomi de danser avec lui mais se met à critiquer sa façon de danser ce qui ne plait pas à la jeune femme. James finit par se bagarrer avec des gens présents sur la piste de danse et la soirée dégénère. Nomi est arrêtée mais James la fait sortir de prison en payant sa caution.',
            'title' => 'Showgirls',
            'year' =>  1995
        ],
        'Moonrise Kingdom' => [
            'actors' => 'Jared Gilman, Kara Hayward, Bruce Willis, Edward Norton, Bill Murray, Frances McDormand',
            'people' => ['Edward Norton'],
            'director' => 'Wes Anderson',
            'duration' => 94,
            'genre' => 'Comédie dramatique',
            'poster' => 'moonrise-kingdom.jpg',
            'rating' => 4,
            'review' => '',
            'synopsis' => 'Durant l\'été 1965, sur une île de la Nouvelle-Angleterre, Sam et Suzy (12 ans) tombent amoureux et décident de s\'enfuir. On apprend par flash-back qu\'ils se sont rencontrés lors d\'une représentation théâtrale, dans laquelle Suzy jouait un corbeau. Tous les deux sont des enfants à problème, n\'ont pas d\'amis et sont rejetés par leurs camarades, mais jouissent d\'une très grande intelligence. Sam sait se débrouiller seul dans la nature et a un don pour la peinture. Il fait partie du groupe de scouts Kaki, mené par le chef de troupe Ward (Edward Norton), professeur de mathématiques en temps normal, et qui a beaucoup de mal à contenir ses troupes, du fait de son manque d\'autorité.',
            'title' => 'Moonrise Kingdom',
            'year' =>  2012
        ],
        'Bienvenue à Gattaca' => [
            'actors' => 'Ethan Hawke, Uma Thurman, Jude Law',
            'director' => 'Andrew Niccol',
            'duration' => 106,
            'genre' => 'Anticipation',
            'poster' => 'bienvenue-a-gattaca.jpeg',
            'rating' => 5,
            'review' => '',
            'synopsis' => 'Dans un monde futuriste, on peut choisir le génotype des enfants. Dans cette société hautement technologique qui pratique l\'eugénisme à grande échelle, les gamètes des parents sont triés et sélectionnés afin de concevoir in vitro des enfants ayant le moins de défauts et le plus d\'avantages possibles.<br />Bien que cela soit officiellement interdit, entreprises et employeurs recourent à des tests ADN discrets afin de sélectionner leurs employés ; les personnes conçues de manière naturelle se retrouvent, de facto, reléguées à des tâches subalternes.',
            'title' => 'Bienvenue à Gattaca',
            'year' =>  1997
        ],
        'Arizona Dream' => [
            'actors' => 'Johnny Depp, Jerry Lewis, Faye Dunaway, Lili Taylor, Vincent Gallo',
            'people' => ['Johnny Depp'],
            'director' => 'Emir Kusturica',
            'duration' => 142,
            'genre' => 'Comédie dramatique',
            'poster' => 'arizona-dream.jpg',
            'rating' => 4,
            'review' => '',
            'synopsis' => 'Après la mort tragique de ses parents dans un accident de voiture, Axel Blackmar a tourné le dos à son passé. Trois ans plus tard, installé à New York, il a trouvé son équilibre et travaille pour le ministère de la Pêche et de la Chasse. Mais son oncle Léo le rappelle dans sa ville natale, en Arizona. Là, Axel va devenir le jouet des rêves de deux femmes qui ont vécu un passé terrible, de ceux de son oncle et peut-être des siens...',
            'title' => 'Arizona Dream',
            'year' =>  1993
        ],
        'The Tree Of Life' => [
            'actors' => 'Brad Pitt, Jessica Chastain, Sean Penn',
            'people' => ['Brad Pitt'],
            'director' => 'Terrence Malick',
            'duration' => 139,
            'genre' => 'Drame',
            'poster' => 'the-tree-of-life.jpg',
            'rating' => 4,
            'review' => '',
            'synopsis' => 'Dans les années 1960, madame O\'Brien (Jessica Chastain) reçoit un télégramme lui apprenant le décès de l\'un de ses fils âgé de dix-neuf ans.<br />Un architecte d\'une grande ville américaine nommé Jack (Sean Penn) se rappelle son enfance par flash-back, et se questionne sur le monde, ce qui occasionne une longue digression où sont évoqués l\'origine du monde, les dinosaures, les éruptions volcaniques, la naissance, les limbes, mais aussi la fin de l\'univers.<br />Dans le Texas des années 1950, un Jack adolescent se heurte à l\'éducation autoritaire d\'un père (Brad Pitt) malgré tout aimant, ingénieur qui rêvait d\'être un grand pianiste. Les rapports conflictuels se cristallisent surtout autour de la mère (Jessica Chastain), aimante et sensible, mais en grande partie soumise au père. Jack, aîné d\'une famille de trois frères, découvre peu à peu la part violente en lui, le poussant à parfois rudoyer ses frères, tout en en retirant un profond remords.',
            'title' => 'The Tree Of Life',
            'year' =>  2011
        ],
        'Fight Club' => [
            'actors' => 'Edward Norton, Brad Pitt, Helena Bonham Carter',
            'people' => ['Edward Norton', 'Brad Pitt', 'Helena Bonham Carter'],
            'director' => 'David Fincher',
            'duration' => 139,
            'genre' => 'Thriller',
            'poster' => 'fight-club.jpg',
            'rating' => 5,
            'review' => '',
            'synopsis' => 'Le film démarre sur le plan du personnage principal (Edward Norton) à qui on a enfoncé un pistolet dans la bouche et dont on entend la voix en monologue qui se remémore comment il en est arrivé là.',
            'title' => 'Fight Club',
            'year' =>  1999
        ],
    ];

    public function load(ObjectManager $manager)
    {
//         foreach (self::MOVIES as $one) {
//             $movie = new Movie();
//             $movie->setActors($one['actors']);
//             $movie->setDirector($one['director']);
//             $movie->setDuration($one['duration']);
//             $movie->setGenre($one['genre']);
//             $movie->setPoster($one['poster']);
//             $movie->setRating($one['rating']);
//             $movie->setReview($one['review']);
//             $movie->setSynopsis($one['synopsis']);
//             $movie->setTitle($one['title']);
//             $movie->setYear($one['year']);

//             if (isset($one['people'])) {
//                 foreach ($one['people'] as $person) {
//                     $role = new Role();
//                     $role->setMovie($movie);
//                     $role->setPerson($this->getReference($person));
//                     $role->setRole(Role::ROLE_ACTOR);
//                     $manager->persist($role);
//                 }
//             }

//             $manager->persist($movie);
//         }

    	$faker = FakerFactory::create();

    	$i = 0;
    	while ($i <= 20) {    		
    		$i++;
			$movie = new Movie();			
			$movie->setActors($faker->firstname.' '.$faker->lastname.', '.$faker->firstname.' '.$faker->lastname);
    		$movie->setDirector($faker->firstname.' '.$faker->lastname);
    		$movie->setDuration($faker->numberBetween(61, 240));
    		$movie->setGenre(self::GENRES[$faker->numberBetween(0, count(self::GENRES)-1)]);
    		$movie->setSynopsis($faker->paragraphs(2, TRUE));
			$wordsInTitle = $faker->numberBetween(1, 3);
    		$movie->setTitle($faker->words($wordsInTitle, TRUE));
			$movie->setYear($faker->numberBetween(1900, 2020));			
			$this->addReference('movie'.$i, $movie);			
			$manager->persist($movie);
    	}    	
    	
    	$manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PersonFixtures::class,
        );
    }
}
