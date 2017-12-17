<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use Elastica\Query\BoolQuery;
use Elastica\Query\Filtered;
use Elastica\Query\Match;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieSearch;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
		$movies = $this->getDoctrine()->getRepository('AppBundle:Movie')->findAll();
		$randomNb = mt_rand(0, count($movies)-1);
		
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
			'movie' => $movies[$randomNb]
        ]);
    }
	
	/**
	* @Route("/all", name="list")
	*/
	public function listAction()
	{
		$movies = $this->getDoctrine()->getRepository('AppBundle:Movie')->getOrderedMoviesByTitle();
		
		return $this->render('default/list.html.twig', [
			'movies' => $movies
		]);
	}

    /**
     * @Route("/actors/all", name="list_actors")
     */
    public function listActorsAction()
    {
        $actors = $this->getDoctrine()->getRepository('AppBundle:Person')->getOrderedActorsByName();

        return $this->render('default/list_actors.html.twig', [
            'actors' => $actors
        ]);
    }
	
	/**
	* @Route("/show/{id}", name="show")
	*/
	public function showAction(Movie $movie)
	{
		return $this->render('default/show.html.twig', [
			'movie' => $movie
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
				'AppBundle\Form\MovieSearchType',
				$movieSearch,
				[
					'action' => $this->generateUrl('search'),
					'method' => 'GET'
				]
			);
		
		$movieSearchForm->handleRequest($request);
		$movieSearch = $movieSearchForm->getData();

		$results = [];
        /** var FOS\ElasticaBundle\Manager\RepositoryManager */
        $repositoryManager = $this->get('fos_elastica.manager.orm');
        /** var FOS\ElasticaBundle\Repository */
        $repository = $repositoryManager->getRepository('AppBundle:Movie');
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
}
