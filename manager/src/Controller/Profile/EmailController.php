<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Controller\ErrorHandler;
use App\Model\User\UseCase\Email;
use Doctrine\ORM;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error;

/**
 * @Route("/profile/email")
 */
class EmailController extends AbstractController
{
    /** @var ErrorHandler */
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="profile.email")
     * @param Request $request
     * @param Email\Request\Handler $handler
     * @return Response
     * @throws ORM\EntityNotFoundException
     * @throws ORM\NonUniqueResultException
     * @throws Error\LoaderError
     * @throws Error\RuntimeError
     * @throws Error\SyntaxError
     */
    public function request(Request $request, Email\Request\Handler $handler): Response
    {
        $command = new Email\Request\Command($this->getUser()->getId());

        $form = $this->createForm(Email\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
                return $this->redirectToRoute('profile');
            } catch (DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/profile/email.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{token}", name="profile.email.confirm")
     * @param string $token
     * @param Email\Confirm\Handler $handler
     * @return Response
     * @throws ORM\EntityNotFoundException
     */
    public function confirm(string $token, Email\Confirm\Handler $handler): Response
    {
        $command = new Email\Confirm\Command($this->getUser()->getId(), $token);

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Email is successfully changed.');
            return $this->redirectToRoute('profile');
        } catch (DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('profile');
        }
    }
}
