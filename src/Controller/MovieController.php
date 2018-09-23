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
            'method' => 'POST',
            'movie_id' => $movies[$randomNb]->getId(),
        ]);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $movieOfPreviousForm = $moviesRepository->find($commentForm->get('movie_id')->getData());
            $comment->setMovie($movieOfPreviousForm);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            $this->get('app.randomovies_mailer')->sendConfirmationCommentMail(
                $user->getEmail(),
                [
                    'comment' => $comment->getComment(),
                    'movie_id' => $movies[$randomNb]->getId()
                ]
            );

            return $this->redirectToRoute('show', ['id' => $movieOfPreviousForm->getId()]);
        }

        // replace this example code with whatever you need
        return $this->render('movie/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'movie' => $movies[$randomNb],
            'comment_form' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/all", name="list_movies")
     */
    public function listAction(Request $request)
    {
        $moviesQueryParams = [];
        if ($request->get('filter') && in_array($request->get('filter'), ['rating', 'users_rating', 'tag'])) {
            if ('tag' === $request->get('filter')) {
                $moviesQueryParams[] = [
                    'andWhere' => 't.id = :tag_id',
                    'value' => [
                        'tag_id',
                        $request->get('tag_id'),
                    ],
                ];
            } else {
                // @todo
            }
        }

        $tags = $this->getDoctrine()->getRepository('Randomovies:Tag')->getDistinctTags();

        $totalMovies = $this->getDoctrine()->getRepository('Randomovies:Movie')->getTotalMovies($moviesQueryParams);

        $totalPages = (int) round($totalMovies / 6);
        $totalPages = $totalPages !== 0 ? $totalPages : 1;

        $page = $request->query->has('page') ? $request->query->get('page') : 1;
        if ($page > $totalPages)
            $page = $totalPages;

        $movies = $this->getDoctrine()->getRepository('Randomovies:Movie')
            ->getOrderedMoviesByTitle(
                ($page-1)*$this->getParameter('max_results_by_page'),
                $this->getParameter('max_results_by_page'),
                $moviesQueryParams
            );

        return $this->render('movie/list.html.twig', [
            'tags' => $tags,
            'totalPages' => $totalPages,
            'page' => $page,
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

            $this->get('app.randomovies_mailer')->sendConfirmationCommentMail(
                $user->getEmail(),
                [
                    'comment' => $comment->getComment(),
                    'movie_id' => $movie->getId()
                ]
            );
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'comment_form' => $commentForm->createView()
        ]);
    }

    private function categoryConverter($category)
    {
        $categoriesConversion = [
            'action' => 'Action',
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
