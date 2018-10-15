<?php

namespace Randomovies\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Randomovies\Form\SuggestionType;
use Randomovies\Entity\Suggestion;

class SuggestionController extends Controller
{
    /**
     * @Route("/suggestion", name="create-suggestion")
     */
    public function createAction(Request $request)
    {
    	$suggestion = new Suggestion();
    	
		$form = $this->createForm(SuggestionType::class, $suggestion);
		
		$form->handleRequest($request);
		
		if (Request::METHOD_POST === $request->getMethod() && $form->isSubmitted() && $form->isValid()) {
			$suggestion->setUser($this->getUser());
			
			$this->getDoctrine()->getManager()->persist($suggestion);
			$this->getDoctrine()->getManager()->flush();
			
			$this->addFlash('success', 'Félicitation! Votre suggestion a bien été enregistrée!');
		}
        		
        return $this->render('suggestion/create.html.twig', [
        	'form' => $form->createView()
        ]);
    }
}
