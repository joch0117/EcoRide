<?php

namespace App\Entity;

use App\Enum\StatusReview;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\Table(name: 'review', indexes: [
    new ORM\Index(columns: ['driver_id']),
    new ORM\Index(columns: ['status'])
])]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 5)]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rating = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column(enumType: StatusReview::class)]
    private ?StatusReview $status = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Trip $trip = null;

    #[ORM\ManyToOne(inversedBy: 'writer_reviews')]
    private ?User $writer = null;

    #[ORM\ManyToOne(inversedBy: 'driver_reviews')]
    private ?User $driver = null;

    public function __construct()
    {
    $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?StatusReview
    {
        return $this->status;
    }

    public function setStatus(StatusReview $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    public function getWriter(): ?User
    {
        return $this->writer;
    }

    public function setWriter(?User $writer): static
    {
        $this->writer = $writer;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }
}
