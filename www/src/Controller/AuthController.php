<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Model\RegisterRequestDTO;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    private AuthService $authService;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;


    public function __construct(AuthService $authService, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->authService = $authService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/api/v1/auth/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $registerRequest = $this->serializer->deserialize($request->getContent(), RegisterRequestDTO::class, 'json');

        $errors = $this->validator->validate($registerRequest);
        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json($errorMessages, Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $this->authService->register($registerRequest),
            Response::HTTP_CREATED
        );
    }
}
