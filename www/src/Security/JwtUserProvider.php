<?php

namespace App\Security;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsernameAndPayload(string $username, array $payload)
    {
        return $this->getUser('id', $payload['id']);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('email', $identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    private function getUser(string $key, mixed $value): UserInterface
    {
        $user = $this->userRepository->findOneBy([$key => $value]);


        if (null === $user) {
            $exception = new UserNotFoundException('User with id ' . json_encode($value, JSON_THROW_ON_ERROR) . ' not found.');
            $exception->setUserIdentifier(json_encode($value, JSON_THROW_ON_ERROR));

            throw $exception;
        }

        return $user;
    }
}