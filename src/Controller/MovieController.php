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
    private function indexAndShowAction(Request $request, Movie $movie)
    {        
        $commentsData = $this->getDoctrine()->getRepository(Comment::class)->getCommentsData($movie->getId());
        $totalComments = $commentsData[0]['totalComments'];
        $usersNote = round($commentsData[0]['usersNote']);
        $commentsByPage = $this->getParameter('max_comments_per_page');
        
        $currentPage = $request->get('cpage') ?? 1;
        $totalPages = ceil($totalComments / $commentsByPage);
        
        $movieComments = $this->getDoctrine()->getRepository(Comment::class)->findBy(
            ['movie' => $movie],
            ['createdAt' => 'DESC'],
            $commentsByPage,
            ($currentPage-1)*$commentsByPage
        );
        
        return [
            'movieComments' => $movieComments,
            'usersNote' => $usersNote,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ];
    }
    
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
        
        $commentsData = $this->indexAndShowAction($request, $movies[$randomNb]);
        
        if ($user = $this->getUser()) {
            if ($user instanceof User) {
                $comment->setUser($user);
            }
        }

        $commentForm = $this->createForm('Randomovies\Form\CommentType', $comment, [
            'method' => 'POST',
        ]);

        $commentForm->handleRequest($request);
        
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {        	
            $movieOfPreviousForm = $moviesRepository->find($request->request->get('randomovies_comment')['movie_id']);
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
            
            $url = $this->generateUrl('show', ['id' => $movieOfPreviousForm->getId()]);
            return $this->redirect($url.'#commentId'.$comment->getId());
        }
        
        // re-création du formulaire avec l'id du nouveau film affiché afin de pouvoir utiliser isValid() plus haut avec le bon id
        $commentForm = $this->createForm('Randomovies\Form\CommentType', $comment, [
    		'method' => 'POST',
    		'movie_id' => $movies[$randomNb]->getId(),
        ]);
		
        // replace this example code with whatever you need
        return $this->render('movie/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'movie' => $movies[$randomNb],
            'comment_form' => $commentForm->createView(),
            'movie_comments' => $commentsData['movieComments'],
            'comments_data' => [
                'usersNote' => $commentsData['usersNote'],
                'totalPages' => $commentsData['totalPages'],
                'currentPage' => $commentsData['currentPage'],
            ],
        ]);
    }
    
    /**
     * @Route("/show/{id}", name="show")     
     */
    public function showAction(Request $request, Movie $movie)
    {        
        $commentsData = $this->indexAndShowAction($request, $movie);        
        
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
            
            return $this->redirect(
                    $this->generateUrl('show', ['id' => $movie->getId()]).'#commentId'.$comment->getId()
                    );
        }
        
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'comment_form' => $commentForm->createView(),
            'movie_comments' => $commentsData['movieComments'],
            'comments_data' => [
                'usersNote' => $commentsData['usersNote'],
                'totalPages' => $commentsData['totalPages'],
                'currentPage' => $commentsData['currentPage'],
            ],
        ]);
    }

    /**
     * @Route("/all", name="list_movies")
     */
    public function listAction(Request $request)
    {
        $moviesQueryParams = [];
        if ($request->get('tag')) {
            $moviesQueryParams['tag'] = $request->get('tag');
        }
        if ($request->get('users_rating')) {
            $moviesQueryParams['users_rating'] = $request->get('users_rating');
        }
        if ($request->get('rating')) {
            $moviesQueryParams['rating'] = $request->get('rating');
        }

        $tags = $this->getDoctrine()->getRepository('Randomovies:Tag')->getDistinctTags();

        $totalMovies = $this->getDoctrine()->getRepository('Randomovies:Movie')->getTotalMovies($moviesQueryParams);
        
        $totalPages = (int) ceil($totalMovies / 6);
        $totalPages = $totalPages !== 0 ? $totalPages : 1;

        $page = $request->query->has('page') ? $request->query->get('page') : 1;
        if ($page > $totalPages)
            $page = $totalPages;

        $movies = $this->getDoctrine()->getRepository('Randomovies:Movie')
            ->getOrderedMoviesByTitle(
                ($page-1)*$this->getParameter('max_movies_per_page'),
                $this->getParameter('max_movies_per_page'),
                $moviesQueryParams
            );

        return $this->render('movie/list.html.twig', [
            'tags' => $tags,
            'totalPages' => $totalPages,
            'page' => $page,
            'movies' => $movies
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
