<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CommentController extends Controller
{
    /**
     * @Route("/comment/delete/{id}", name="comment-delete")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        if ($this->getUser() !== $comment->getUser()) {
            throw new UnauthorizedHttpException('Vous n\'êtes pas autorisé à faire ça !');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('user-account');
    }

    /**
     * @Route("/comment/edit/{id}", name="comment-edit")
     */
    public function editAction(Request $request, Comment $comment)
    {
        if ($this->getUser() !== $comment->getUser()) {
            throw new UnauthorizedHttpException('Vous n\'êtes pas autorisé à faire ça !');
        }

        $commentForm = $this->createForm('Randomovies\Form\CommentType', $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('user-account');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'comment_form' => $commentForm->createView()
        ]);
    }
}
