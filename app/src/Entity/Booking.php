<?php

namespace App\Entity;

use App\Enum\StateBooking;
use App\Enum\StatusFeedback;
use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ORM\Table(name: 'booking', indexes: [
    new ORM\Index(columns: ['trip_id']),
    new ORM\Index(columns: ['user_id'])
])]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seats = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: StateBooking::class)]
    private ?StateBooking $state = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: StatusFeedback::class)]
    private ?StatusFeedback $feedback_status = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;

        return $this;
    }

    public function getState(): ?StateBooking
    {
        return $this->state;
    }

    public function setState(StateBooking $state): static
    {
        $this->state = $state;

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

    public function getFeedbackStatus(): ?StatusFeedback
    {
        return $this->feedback_status;
    }

    public function setFeedbackStatus(StatusFeedback $feedback_status): static
    {
        $this->feedback_status = $feedback_status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
}
