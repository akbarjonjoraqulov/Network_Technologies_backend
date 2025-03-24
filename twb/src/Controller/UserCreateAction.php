<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Entity\User;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UserCreateAction extends AbstractController
{
    public function __construct(
        readonly private UserManager $userManager,
        readonly private UserFactory $userFactory,
        readonly private ValidatorInterface $validator,
    ) {
    }
    #[NoReturn]
    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->deserialize($request->getContent(), User::class, 'json');

        $this->validator->validate($data);

        $user = $this->userFactory->create(
            $data->getGivenName(),
            $data->getFamilyName(),
            $data->getPassword(),
            $data->getEmail(),
            $data->getVideo()->toArray()
        );

        $this->userManager->save($user, true);

        return new JsonResponse([
            'id' => $user->getId(),
            'familyName' => $user->getFamilyName(),
            'givenName' => $user->getGivenName(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
        ], Response::HTTP_CREATED);
    }
}
