<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
            
            $redirect = $commentForm->get('referer')->getData();
            $redirect = preg_match('/#commentId/', $redirect) ? $redirect : $redirect.'#commentId'.$comment->getId();            
            	
            return $this->redirect($redirect);
        }
        
        $referer = $request->headers->get('referer');
                
        if ($referer !== $request->getUri()) {
        	$commentForm = $this->createForm('Randomovies\Form\CommentType', $comment, [
        		'referer' => $referer,		
        	]);
        }
        
        $backPath = $commentForm->get('referer')->getData();
        $backPath = preg_match('/#commentId/', $backPath) ? $backPath : $backPath.'#commentId'.$comment->getId();
		
        return $this->render('comment/edit.html.twig', [
        	'back_path' => $backPath,
            'comment' => $comment,
            'comment_form' => $commentForm->createView()
        ]);
    }
}
