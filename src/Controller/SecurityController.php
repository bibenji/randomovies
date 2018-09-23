<?php	

namespace Randomovies\Controller;

use Ramsey\Uuid\Uuid;
use Randomovies\Entity\User;
use Randomovies\Form\Security\PasswordForgottenType;
use Randomovies\Form\Security\PasswordRecoverType;
use Randomovies\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
	/**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', array(
			'last_username' => $lastUsername,
			'error'         => $error,
		));
	}

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('app.randomovies_mailer')->sendRegistrationMail(
                $user->getEmail()
            );

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * TODO : supprimer cette route
     *
     * @Route("/login-user", name="user-login")
     */
    public function loginUserAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/password/forgotten", name="password_forgotten")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function passwordForgottenAction(Request $request)
    {
        $form = $this->createForm(PasswordForgottenType::class);

        $form->handleRequest($request);

        if ($request->getMethod() === Request::METHOD_POST && $form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            $user = $this->getDoctrine()->getRepository('Randomovies:User')->findOneBy([
                'email' => $email
            ]);

            if (null !== $user) {
                $user->setIsActive(false);

//                $token = random_bytes(10);
                $token = Uuid::uuid4()->toString();
                $user->setToken($token);

                $user->setTokenAskedAt(new \DateTime());

                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                $this->get('app.randomovies_mailer')->sendPasswordRecoverMail(
                    $user->getEmail(),
                    ['token' => $token]
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('security/password_forgotten.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/password/recover/{token}", name="password_recover")
     * @param Request $request
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function passwordRecoverAction(Request $request, $token)
    {
        $user = $this->getDoctrine()->getRepository('Randomovies:User')->findOneBy(['token' => $token]);

        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(PasswordRecoverType::class);

        $form->handleRequest($request);

        if ($request->getMethod() === Request::METHOD_POST && $form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->getData()['plainPassword'];
            $user->setPlainPassword($plainPassword);

            $password = $this->get('security.password_encoder')->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            $user->setIsActive(true);
            $user->setToken(null);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->get('app.randomovies_mailer')->sendConfirmationPasswordRecoverMail(
                $user->getEmail()
            );

            return $this->redirectToRoute('login');
        }

        return $this->render('security/password_recover.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
