<?php

declare(strict_types=1);

namespace App\Controller\Api\Auth;

use App\Controller\ErrorHandler;
use App\Model\User\UseCase\SignUp;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;
    /** @var ValidatorInterface */
    private $validator;
    /** @var ErrorHandler */
    private $errors;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ErrorHandler $errors)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->errors = $errors;
    }

    /**
     * @Route("/auth/signup", name="auth.signup", methods={"POST"})
     * @param Request $request
     * @param SignUp\Request\Handler $handler
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function request(Request $request, Signup\Request\Handler $handler): Response
    {
        /** @var SignUp\Request\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), SignUp\Request\Command::class, 'json');
        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        try {
            $handler->handle($command);
        } catch (DomainException $e) {
            $this->errors->handle($e);
            return $this->json(['error' => ['message' => $e->getMessage()]], 400);
        }

        return $this->json([], 201);
    }
}
