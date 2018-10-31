<?php

namespace Randomovies\Controller\Admin;

use Randomovies\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Person controller.
 *
 * @Route("admin/person")
 */
class AdminPersonController extends Controller
{
    /**
     * Lists all person entities.
     *
     * @Route("/", name="admin_person_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {        
        $perPage = $this->getParameter('admin_max_results_per_page');
        $totalPeople = $this->getDoctrine()->getRepository(Person::class)->getTotalPeople();
        $currentPage = $request->get('page') ?? 1;
        $totalPages = ceil($totalPeople / $perPage);
        
        $people = $this->getDoctrine()->getRepository(Person::class)->findBy(
            [],
            ['lastname' => 'ASC'],
            $perPage,
            ($currentPage-1)*$perPage
        );
        
        return $this->render('admin/person/index.html.twig', [
            'people' => $people,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * Creates a new person entity.
     *
     * @Route("/new", name="admin_person_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm('Randomovies\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('admin_person_show', array('id' => $person->getId()));
        }

        return $this->render('admin/person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing person entity.
     *
     * @Route("/{id}/edit", name="admin_person_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);
        $mediasDirectory = $this->getParameter('medias_directory');
        $editForm = $this->createForm('Randomovies\Form\PersonType', $person, ['medias_directory' => $mediasDirectory]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $person = $editForm->getData();
            foreach ($person->getMedias() as $media) {
                if (null === $media->getPath()) {
                    $person->removeMedia($media);
                }
                elseif ($media->getPath() instanceof File) {
                    $file = $media->getPath();
                    if (null === $media->getName() || $media->getName() === '') {
                        $media->setName($file->getClientOriginalName());
                    }

                    $newPath = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('medias_directory'),
                        $newPath
                    );
                    $media->setPath($newPath);

                    $entityManager->persist($media);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_person_edit', array('id' => $person->getId()));
        }

        return $this->render('admin/person/edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a person entity.
     *
     * @Route("/{id}", name="admin_person_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Person $person)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush();
        }

        return $this->redirectToRoute('admin_person_index');
    }

    /**
     * Creates a form to delete a person entity.
     *
     * @param Person $person The person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_person_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
