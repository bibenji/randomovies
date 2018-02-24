<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Comment;
use Randomovies\Entity\Person;
use Randomovies\Entity\User;
use Elastica\Query\BoolQuery;
use Elastica\Query\Filtered;
use Elastica\Query\Match;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Randomovies\Entity\Movie;
use Randomovies\Entity\MovieSearch;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $category = $request->get('category');

        $moviesRepository = $this->getDoctrine()->getRepository('Randomovies:Movie');

		if ($category !== null) {
            $movies = $moviesRepository->findBy([
                'genre' => $this->categoryConverter($request->get('category'))
            ]);
        } else {
		    $movies = $moviesRepository->findAll();
        }

		$randomNb = mt_rand(0, count($movies)-1);



        $comment = new Comment();
        $comment->setMovie($movies[$randomNb]);

        if ($user = $this->getUser()) {
            if ($user instanceof User) {
                $comment->setUser($user);
            }
        }

        $commentForm = $this->createForm('Randomovies\Form\CommentType', $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
			'movie' => $movies[$randomNb],
            'comment_form' => $commentForm->createView()
        ]);
    }
	
	/**
	* @Route("/all", name="list")
	*/
	public function listAction()
	{
		$movies = $this->getDoctrine()->getRepository('Randomovies:Movie')->getOrderedMoviesByTitle();
		
		return $this->render('default/list.html.twig', [
			'movies' => $movies
		]);
	}

    /**
     * @Route("/actors/all", name="list_actors")
     */
    public function listActorsAction()
    {
        $actors = $this->getDoctrine()->getRepository('Randomovies:Person')->getOrderedActorsByName();

        return $this->render('default/list_actors.html.twig', [
            'actors' => $actors
        ]);
    }
	
	/**
	* @Route("/show/{id}", name="show")
	*/
	public function showAction(Request $request, Movie $movie)
	{
        $comment = new Comment();
        $comment->setMovie($movie);

        if ($user = $this->getUser()) {
            if ($user instanceof User) {
                $comment->setUser($user);
            }
        }

        $commentForm = $this->createForm('Randomovies\Form\CommentType', $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);
        }

		return $this->render('default/show.html.twig', [
			'movie' => $movie,
            'comment_form' => $commentForm->createView()
		]);
	}

    /**
     * @Route("/actor/{id}", name="show_actor")
     */
    public function showActorAction(Person $person)
    {
        return $this->render('default/show_actor.html.twig', [
            'actor' => $person
        ]);
    }
	
	/**
	* @Route("/search", name="search")
	*/
	public function searchAction(Request $request)
	{
		$movieSearch = new MovieSearch();
		
		$movieSearchForm = $this->get('form.factory')
			->createNamed(
				'',
				'Randomovies\Form\MovieSearchType',
				$movieSearch,
				[
					'action' => $this->generateUrl('search'),
					'method' => 'POST'
				]
			);

		dump($request);

		
		$movieSearchForm->handleRequest($request);
		$movieSearch = $movieSearchForm->getData();

		dump($movieSearchForm);

		$results = [];
        /** var FOS\ElasticaBundle\Manager\RepositoryManager */
        $repositoryManager = $this->get('fos_elastica.manager.orm');
        /** var FOS\ElasticaBundle\Repository */
        $repository = $repositoryManager->getRepository('Randomovies:Movie');
        $results = $repository->findMoviesWithSearchData($movieSearch);

		return $this->render('default/search.html.twig', [
			'results' => $results,
            'movieSearchForm' => $movieSearchForm->createView(),
		]);
	}

    /**
     * @Route("/search_test", name="searchTest")
     */
    public function searchTestAction(Request $request)
    {
        $index = $this->get('fos_elastica.index.randomovies');
        $result = $index->search("big");
        dump($result);

        $finder = $this->get('fos_elastica.finder.randomovies.movie');
        $boolQuery = new BoolQuery();
        $fieldTitle = new Match();
        $fieldTitle->setField('title', 'temps');
        $boolQuery->addShould($fieldTitle);
        $data = $finder->find($boolQuery);
        dump($data);

        $query_part = new BoolQuery();
        $query_part->addShould(
            new Term([
                'title' => [
                    'value' => 'temps',
                    'boost' => 3
                ]
            ])
        );
        $query_part->addShould(
            new Term([
                'synopsis' => [
                    'value' => 'temps'
                ]
            ])
        );
        $filters = new BoolQuery();
        $filters->addMust(
            new Term([
                'language' => 'fr'
            ])
        );
//        $filters->addMust(
//            new \Elastica\Filter\NumericRange('published_at', array(
//                'lte' => date('c'),
//            ))
//        );

//        $query = new Filtered($query_part, $filters);
//        $query = new Filtered($query_part);
        $result2 = $index->search($query_part, $filters);
        dump($result2);

        dump("finir de lire : http://www.afsy.fr/avent/2013/20-elasticsearch-dans-votre-Symfony2");

        exit;
    }

    private function categoryConverter($category)
    {
        $categoriesConversion = [
            'science-fiction' => 'Science-fiction',
            'drame' => 'Drame',
            'comedie-dramatique' => 'Comédie dramatique',
            'comedie' => 'Comédie',
            'anticipation' => 'Anticipation',
            'thriller' => 'Thriller'
        ];

        return $categoriesConversion[$category];
    }
}
