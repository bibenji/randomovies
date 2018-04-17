<?php

namespace Randomovies\Controller;

use Randomovies\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @Route("/tag", name="tag_create", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     */
    public function createTag(Request $request)
    {
        if ($request->request->has("name")) {
            $name = strtolower($request->request->get("name"));

            $tag = $this->getDoctrine()->getRepository('Randomovies:Tag')->findOneBy(["name" => $name]);
            if (null === $tag) {
                $tag = new Tag();
                $tag->setName($name);
                $this->getDoctrine()->getManager()->persist($tag);
                $this->getDoctrine()->getManager()->flush();

                $data = $this->get('serializer')->serialize($tag, 'json');
                return new JsonResponse($data, 201);
            } else {
                $data = $this->get('serializer')->serialize($tag, 'json');
                return new JsonResponse($data, 409);
            }
        } else {
            return new JsonResponse(null, 400);
        }
    }

    /**
     * @Route("/tag/{id}", name="tag_delete", methods={"DELETE"})
     * @param Request $request
     * @param Tag $tag
     * @return JsonResponse
     */
    public function removeTag(Request $request, Tag $tag)
    {
        $this->getDoctrine()->getManager()->remove($tag);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(null, 204);
    }
}