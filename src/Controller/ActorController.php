<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class ActorController extends Controller
{
    /**
     * @Route("/acteurs", name="list_actors")
     */
    public function listActorsAction()
    {
        $actors = $this->getDoctrine()->getRepository('Randomovies:Person')->getOrderedActorsByName();

        return $this->render('actor/list_actors.html.twig', [
            'actors' => $actors
        ]);
    }

    /**
     * @Route("/acteur/{id}", name="show_actor")
     */
    public function showActorAction(Request $request, Person $person)
    {
        $absoluteActorDir = $this->getParameter('kernel.project_dir') . '/public/images/actors/' . $person->getId() . '/';
        $relativeActorDir = '/images/actors/' . $person->getId() . '/';
        
        if (file_exists($absoluteActorDir)) {
            $finder = new Finder();
            $finder->files()->in($absoluteActorDir);
        } else {
            $finder = [];
        }
        
        return $this->render('actor/show_actor.html.twig', [
            'actor' => $person,
            'actor_dir' => $relativeActorDir,
            'finder' => $finder,    
            'referer' => $request->headers->get('referer'),        
        ]);
    }
}
