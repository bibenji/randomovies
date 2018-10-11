<?php

namespace Randomovies\Controller;

use Randomovies\Form\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user-account")
     */
    public function accountAction(Request $request)
    {
        return $this->render('user/account.html.twig');
    }

    /**
     * @Route("/user/edit", name="user-edit")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($request->getMethod() === Request::METHOD_POST && $form->isSubmitted() && $form->isValid()) {

            if (null != $user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            if ($file = $request->files->get('account')['avatar']) {

                if (null != $user->getPhoto()) {
                    unlink($this->getParameter('users_photos_directory').'/'.$user->getPhoto());
                    unlink($this->getParameter('users_photos_directory').'/medium/'.$user->getPhoto());
                    unlink($this->getParameter('users_photos_directory').'/small/'.$user->getPhoto());
                }

                /** @var UploadedFile $file */
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('users_photos_directory'),
                    $fileName
                );

                $user->setPhoto($fileName);
            }

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}