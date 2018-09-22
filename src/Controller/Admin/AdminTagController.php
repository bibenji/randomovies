<?php

namespace Randomovies\Controller\Admin;

use Randomovies\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tag controller.
 *
 * @Route("admin/tag")
 */
class AdminTagController extends Controller
{
    /**
     * Lists all tag entities.
     *
     * @Route("/", name="admin_tag_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('Randomovies:Tag')->findAll();

        return $this->render('admin/tag/index.html.twig', array(
            'tags' => $tags,
        ));
    }

    /**
     * Creates a new tag entity.
     *
     * @Route("/new", name="admin_tag_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm('Randomovies\Form\TagType', $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($tag);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_tag_index', array('id' => $tag->getId()));
        }

        return $this->render('admin/tag/new.html.twig', array(
            'tag' => $tag,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tag entity.
     *
     * @Route("/{id}/edit", name="admin_tag_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tag $tag)
    {
        $deleteForm = $this->createDeleteForm($tag);
        $editForm = $this->createForm('Randomovies\Form\TagType', $tag);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->persist($tag);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_tag_index');
        }

        return $this->render('admin/tag/edit.html.twig', array(
            'tag' => $tag,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tag entity.
     *
     * @Route("/{id}", name="admin_tag_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, Tag $tag)
    {
        $form = $this->createDeleteForm($tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() || Request::METHOD_GET === $request->getMethod()) {
            $this->getDoctrine()->getManager()->remove($tag);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('admin_tag_index');
    }

    /**
     * Creates a form to delete a tag entity.
     *
     * @param Tag $tag The tag entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tag $tag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tag_delete', array('id' => $tag->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Permet de créer des tags en ajax via le formulaire de création de film
     *
     * @Route("/tag", name="tag_create", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
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
}
