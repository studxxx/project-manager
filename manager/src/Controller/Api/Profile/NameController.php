<?php

declare(strict_types=1);

namespace App\Controller\Api\Profile;

use App\Model\User\UseCase\Name;
use Doctrine\ORM\EntityNotFoundException;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NameController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;
    /** @var ValidatorInterface */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @OA\Put(
     *     path="/profile/name",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              required={"first", "last"},
     *              @OA\Property(property="first", type="string"),
     *              @OA\Property(property="last", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success response"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Errors",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="object", nullable=true,
     *                  @OA\Property(property="code", type="integer"),
     *                  @OA\Property(property="message", type="string"),
     *              ),
     *              @OA\Property(property="violations", type="array", nullable=true, @OA\Items(
     *                  type="objects",
     *                  @OA\Property(property="propertyPath", type="string"),
     *                  @OA\Property(property="title", type="string"),
     *              ))
     *          )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @Route("/profile/name", name="profile.name", methods={"PUT"})
     * @param Request $request
     * @param Name\Handler $handler
     * @return Response
     * @throws EntityNotFoundException
     */
    public function name(Request $request, Name\Handler $handler): Response
    {
        /** @var Name\Command $command */
        $command = $this->serializer->deserialize($request->getContent(), Name\Command::class, 'json', [
            'object_to_populate' => new Name\Command($this->getUser()->getId()),
            'ignored_attributes' => ['id'],
        ]);

        $violations = $this->validator->validate($command);
        if (count($violations)) {
            $json = $this->serializer->serialize($violations, 'json');
            return new JsonResponse($json, 400, [], true);
        }

        $handler->handle($command);

        return $this->json([]);
    }
}
