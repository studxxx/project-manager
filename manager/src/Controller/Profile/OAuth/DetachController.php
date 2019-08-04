<?php

declare(strict_types=1);

namespace App\Controller\Profile\OAuth;

use App\Controller\ErrorHandler;
use App\Model\User\UseCase\Network\Detach;
use Doctrine\ORM\EntityNotFoundException;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/oauth")
 */
class DetachController extends AbstractController
{
    /** @var ErrorHandler */
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/detach/{network}/{identity}", name="profile.oauth.detach", methods={"DELETE"})
     * @param Request $request
     * @param string $network
     * @param string $identity
     * @param Detach\Handler $handler
     * @return Response
     * @throws EntityNotFoundException
     */
    public function connect(Request $request, string $network, string $identity, Detach\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('profile');
        }

        $command = new Detach\Command(
            $this->getUser()->getId(),
            $network,
            $identity
        );

        try {
            $handler->handle($command);
            return $this->redirectToRoute('profile');
        } catch (DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('profile');
        }
    }
}
