<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

class ConfirmTokenSender
{
    /** @var Swift_Mailer */
    private $mailer;
    /** @var Environment */
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Email$email, string $token): void
    {
        $message = (new Swift_Message('Sign Up Confirmation'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/signup.html.twig', [
                'token' => $token,
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new RuntimeException('Unable to send message.');
        }
    }
}
