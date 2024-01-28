<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterRequestDTO
{
    #[NotBlank]
    private string $name;

    #[NotBlank]
    #[Email(message: "Email is not valid")]
    private string $email;

    #[NotBlank]
    #[Length(min: 6, minMessage: "Password must be at least 6 characters long")]
    private string $password;

    #[NotBlank]
    #[EqualTo(propertyPath: "password", message: "Passwords don't match")]
    private string $confirmPassword;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

}