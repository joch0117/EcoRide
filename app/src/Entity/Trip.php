<?php

namespace App\Entity;

use App\Enum\StatusTrip;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TripRepository::class)]
#[ORM\Table(name: 'trip', indexes: [
    new ORM\Index(columns: ['departure_city', 'departure_datetime']),
    new ORM\Index(columns: ['arrival_city']),
    new ORM\Index(columns: ['driver_id'])
])]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    #[ORM\Column(length: 80)]
    private ?string $departure_city = null;

    #[ORM\Column(length: 80)]
    private ?string $arrival_city = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departure_datetime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrival_datetime = null;

    #[Assert\Positive]
    #[ORM\Column(type:'integer')]
    private ?int $duration=null;

    #[Assert\NotNull]
    #[Assert\Positive]
    #[ORM\Column]
    private ?int $price = null;

    #[Assert\Range(min: 1, max: 8)]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seats_available = null;

    #[ORM\Column]
    private ?bool $is_ecological = null;

    #[ORM\Column(enumType: StatusTrip::class)]
    private ?StatusTrip $status = null;

    #[ORM\ManyToOne(inversedBy: 'driver_trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'trip')]
    private Collection $bookings;

    /**
     * @var Collection<int, IncidentReport>
     */
    #[ORM\OneToMany(targetEntity: IncidentReport::class, mappedBy: 'trip')]
    private Collection $incidentReports;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: CreditTransaction::class)]
    private Collection $creditTransactions;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'trip')]
    private Collection $reviews;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->incidentReports = new ArrayCollection();
        $this->creditTransactions = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->status = StatusTrip::SCHEDULED;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureCity(): ?string
    {
        return $this->departure_city;
    }

    public function setDepartureCity(string $departure_city): static
    {
        $this->departure_city = $departure_city;

        return $this;
    }

    public function getArrivalCity(): ?string
    {
        return $this->arrival_city;
    }

    public function setArrivalCity(string $arrival_city): static
    {
        $this->arrival_city = $arrival_city;

        return $this;
    }

    public function getDepartureDatetime(): ?\DateTimeInterface
    {
        return $this->departure_datetime;
    }

    public function setDepartureDatetime(\DateTimeInterface $departure_datetime): static
    {
        $this->departure_datetime = $departure_datetime;

        return $this;
    }

    public function getArrivalDatetime(): ?\DateTimeInterface
    {
        return $this->arrival_datetime;
    }

    public function setArrivalDatetime(\DateTimeInterface $arrival_datetime): static
    {
        $this->arrival_datetime = $arrival_datetime;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->seats_available;
    }

    public function setSeatsAvailable(int $seats_available): static
    {
        $this->seats_available = $seats_available;

        return $this;
    }

    public function isEcological(): ?bool
    {
        return $this->is_ecological;
    }

    public function setIsEcological(bool $is_ecological): static
    {
        $this->is_ecological = $is_ecological;

        return $this;
    }

    public function getStatus(): ?StatusTrip
    {
        return $this->status;
    }

    public function setStatus(StatusTrip $status): static
    {
        $this->status = $status;

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

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setTrip($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getTrip() === $this) {
                $booking->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IncidentReport>
     */
    public function getIncidentReports(): Collection
    {
        return $this->incidentReports;
    }

    public function addIncidentReport(IncidentReport $incidentReport): static
    {
        if (!$this->incidentReports->contains($incidentReport)) {
            $this->incidentReports->add($incidentReport);
            $incidentReport->setTrip($this);
        }

        return $this;
    }

    public function removeIncidentReport(IncidentReport $incidentReport): static
    {
        if ($this->incidentReports->removeElement($incidentReport)) {
            // set the owning side to null (unless already changed)
            if ($incidentReport->getTrip() === $this) {
                $incidentReport->setTrip(null);
            }
        }

        return $this;
    }

    public function getCreditTransactions(): Collection
    {
        return $this->creditTransactions;
    }

    public function addCreditTransaction(CreditTransaction $creditTransaction): static
    {
        if (!$this->creditTransactions->contains($creditTransaction)) {
            $this->creditTransactions[] = $creditTransaction;
            $creditTransaction->setTrip($this);
        }

        return $this;
    }

    public function removeCreditTransaction(CreditTransaction $creditTransaction): static
    {
        if ($this->creditTransactions->removeElement($creditTransaction)) {
            if ($creditTransaction->getTrip() === $this) {
                $creditTransaction->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setTrip($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getTrip() === $this) {
                $review->setTrip(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;
        return $this;
    }
}
