<?php

namespace App\Service;

use App\Entity\User;
use App\Model\RegisterRequest;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthService
{
    private UserRepository $userRepository;

    private ValidatorInterface $validator;

    private UserPasswordHasherInterface $hasher;

    private AuthenticationSuccessHandler $successHandler;


    public function __construct(
        UserRepository $userRepository,
        ValidatorInterface $validator,
        UserPasswordHasherInterface  $passwordHasher,
        AuthenticationSuccessHandler $authenticationSuccessHandler
    )
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->hasher = $passwordHasher;
        $this->successHandler = $authenticationSuccessHandler;
    }

    public function register(RegisterRequest $registerRequest): string
    {
        if ($this->userRepository->getByEmail($registerRequest->getEmail())) {
            throw new BadRequestHttpException('User already exists');
        }

        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setName($registerRequest->getName());
        $user->setEmail($registerRequest->getEmail());
        $user->setPassword($this->hasher->hashPassword($user, $registerRequest->getPassword()));

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            throw new BadRequestHttpException(implode(', ', $errorMessages));
        }

        $this->userRepository->create($user);

        return $this->successHandler->handleAuthenticationSuccess($user)->getContent();
    }

}