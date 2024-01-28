<?php

namespace App\Model;

class UpdateFeedbackRequestDTO
{
    private string $status;
    private ?string $comment = null;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

}