<?php

namespace App\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class FeedbackRequestDTO
{
    #[NotBlank(message: 'Topic cannot be empty.')]
    #[Assert\Length(min: 3, max: 255)]
    private string $topic;

    #[NotBlank(message: 'User_id cannot be empty.')]
    private int $user_id;

    #[NotBlank(message: 'Message cannot be empty.')]
    private string $message;

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

}