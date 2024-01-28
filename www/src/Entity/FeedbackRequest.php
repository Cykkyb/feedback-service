<?php

namespace App\Entity;

use App\Repository\FeedbackRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: FeedbackRequestRepository::class)]
#[ORM\HasLifecycleCallbacks]
class FeedbackRequest
{
    const STATUS_NEW = 'новая';
    const STATUS_PROCESSING = 'в работе';
    const STATUS_RESOLVED = 'решено';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $topic;

    #[ORM\Column(type: "integer")]
    private int $user_id;

    #[ORM\Column(type: "string")]
    private string $message;

    #[ORM\Column(type: "string")]
    private string $status;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    public function getUser(): int
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTime();
    }
}
