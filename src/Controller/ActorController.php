<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ActorController extends Controller
{
    /**
     * @Route("/actors/all", name="list_actors")
     */
    public function listActorsAction()
    {
        $actors = $this->getDoctrine()->getRepository('Randomovies:Person')->getOrderedActorsByName();

        return $this->render('actor/list_actors.html.twig', [
            'actors' => $actors
        ]);
    }

    /**
     * @Route("/actor/{id}", name="show_actor")
     */
    public function showActorAction(Person $person)
    {
        return $this->render('actor/show_actor.html.twig', [
            'actor' => $person
        ]);
    }
}
