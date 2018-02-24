<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Comment;
use Randomovies\Entity\Person;
use Randomovies\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Randomovies\Entity\Movie;

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
