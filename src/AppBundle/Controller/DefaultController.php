<?php

namespace AppBundle\Controller;

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
		// $movies = $this->getDoctrine()->getRepository('AppBundle:Movie')->findAll();
		$movies = $this->getDoctrine()->getRepository('AppBundle:Movie')->getOrderedMoviesByTitle();
		
		return $this->render('default/list.html.twig', [
			'movies' => $movies
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
		
		$elasticaManager = $this->container->get('fos_elastica.manager');
        $results = $elasticaManager->getRepository('AppBundle:Movie')->search($movieSearch);
		
		return $this->render('default/search.html.twig', [
			'results' => $results,
            'movieSearchForm' => $movieSearchForm->createView(),
		]);
	}
}
