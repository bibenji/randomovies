<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Comment;
use Randomovies\Entity\Movie;
use Randomovies\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
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

        $commentForm = $this->createForm('Randomovies\Form\CommentType', $comment, [
            'method' => 'POST'
        ]);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);
        }

        // replace this example code with whatever you need
        return $this->render('movie/show.html.twig', [
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

        return $this->render('movie/list.html.twig', [
            'movies' => $movies
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

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'comment_form' => $commentForm->createView()
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
