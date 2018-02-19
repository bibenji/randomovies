<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Media;
use AppBundle\Entity\Person;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends Controller
{
    /**
     * @Route("/admin/person/{person}/media/delete/{media}", name="media-delete")
     */
    public function deleteAction(Request $request, Person $person, Media $media)
    {
        $person->removeMedia($media);
        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
