<?php

namespace Randomovies\Service;

use Symfony\Bridge\Twig\TwigEngine;

class Mailer
{
    private $swiftMailer;
    private $mailerSender;
    private $twig;

    public function __construct(\Swift_Mailer $swiftMailer, $mailerSender, TwigEngine $twig)
    {
//        dump(get_class($twig)); exit;
        $this->swiftMailer = $swiftMailer;
        $this->mailerSender = $mailerSender;
        $this->twig = $twig;
    }

    public function sendRegistrationMail($to, $params = [])
    {
        $this->createAndSendMessage(
            'Randomovies - Création de votre compte',
            $to,
            $this->twig->render(
                'emails/registration.html.twig',
                $params
            )
        );

    }

    public function sendPasswordRecoverMail($to, $params = [])
    {
        $this->createAndSendMessage(
            'Randomovies - Ré-initialisation de votre mot de passe',
            $to,
            $this->twig->render(
                'emails/password_send_token.html.twig',
                $params
            )
        );
    }

    public function sendConfirmationPasswordRecoverMail($to, $params = [])
    {
        $this->createAndSendMessage(
            'Randomovies - Ré-initialisation de votre mot de passe (suite)',
            $to,
            $this->twig->render(
                'emails/password_recover_ok.html.twig',
                $params
            )
        );
    }

    public function sendConfirmationCommentMail($to, $params = [])
    {
        $this->createAndSendMessage(
            'Randomovies - Commentaire enregistré',
            $to,
            $this->twig->render(
                'emails/comment_ok.html.twig',
                $params
            )
        );
    }

    private function createAndSendMessage($subject, $to, $body)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($this->mailerSender)
            ->setTo($to)
            ->setBody($body,'text/html')
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $this->swiftMailer->send($message);
    }
}
