<?php

namespace Randomovies\Controller\Admin;

use Randomovies\Entity\Media;
use Randomovies\Entity\Person;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class AdminMediaController extends Controller
{
    /**
     * @Route("/admin/person/{person}/media/delete/{media}", name="media-delete")
     */
    public function deleteAction(Request $request, Person $person, Media $media)
    {
//        Remove media from hard disk
        $fs = new Filesystem();
        $fs->remove($this->getParameter('medias_directory').'/'.$media->getPath());

        $person->removeMedia($media);
        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
