<?php

namespace Randomovies\Controller\Admin;

use Randomovies\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Movie controller.
 *
 * @Route("admin/movie")
 */
class AdminMovieController extends Controller
{
    /**
     * Lists all movie entities.
     *
     * @Route("/", name="admin_movie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movies = $em->getRepository('Randomovies:Movie')->findAll();

        return $this->render('admin/movie/index.html.twig', array(
            'movies' => $movies,
        ));
    }

    /**
     * Creates a new movie entity.
     *
     * @Route("/new", name="admin_movie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $movie = new Movie();

        $form = $this->createForm('Randomovies\Form\MovieType', $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			if ($file = $movie->getPoster()) {								
				$fileName = md5(uniqid()).'.'.$file->guessExtension();
				$file->move(
					$this->getParameter('posters_directory'),
					$fileName
				);
				$movie->setPoster($fileName);
			}
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('admin_movie_show', array('id' => $movie->getId()));
        }

        return $this->render('admin/movie/new.html.twig', array(
            'movie' => $movie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing movie entity.
     *
     * @Route("/{id}/edit", name="admin_movie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Movie $movie)
    {
        $deleteForm = $this->createDeleteForm($movie);
        $editForm = $this->createForm('Randomovies\Form\MovieType', $movie, ['edit' => true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($file = $editForm->get('newPoster')->getData()) {
//                First, delete old poster
                $fs = new Filesystem();
                $fs->remove($this->getParameter('posters_directory').'/'.$movie->getPoster());

//                Then, save new poster
				$fileName = md5(uniqid()).'.'.$file->guessExtension();
				$file->move(
					$this->getParameter('posters_directory'),
					$fileName
				);
				$movie->setPoster($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('admin_movie_edit', array('id' => $movie->getId()));
        }

        return $this->render('admin/movie/edit.html.twig', array(
            'movie' => $movie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a movie entity.
     *
     * @Route("/{id}", name="admin_movie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Movie $movie)
    {
        $form = $this->createDeleteForm($movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            Delete poster for that movie
            $fs = new Filesystem();
            $fs->remove($this->getParameter('posters_directory').'/'.$movie->getPoster());

            $em = $this->getDoctrine()->getManager();
            $em->remove($movie);
            $em->flush();
        }

        return $this->redirectToRoute('admin_movie_index');
    }

    /**
     * Creates a form to delete a movie entity.
     *
     * @param Movie $movie The movie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Movie $movie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_movie_delete', array('id' => $movie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
