<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\Table(name:'user')]
#[ORM\Index(columns:['email'])]
#[ORM\Index(columns:['username'])]
#[ORM\Index(columns:['is_driver'])]
#[ORM\Index(columns:['is_passenger'])]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 180, unique:true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    private ?string $plainPassword = null;
    
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    #[ORM\Column(length: 50)]
    private ?string $username = null;
    
    #[Assert\Length(max: 50)]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $surname = null;
    
    #[Assert\Length(max: 50)]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstname = null;

    #[Assert\Length(min: 10, max: 20)]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_birth = null;

    #[ORM\Column(length: 255)]
    private ?string $photo_url = null;

    #[ORM\Column]
    private ?bool $is_passenger = null;

    #[ORM\Column]
    private ?bool $is_driver = null;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[ORM\Column]
    private ?int $credit = null;


    #[ORM\Column (type: 'boolean')]
    private ?bool $is_suspended = false;

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'user')]
    private Collection $vehicles;

    /**
     * @var Collection<int, Trip>
     */
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'driver')]
    private Collection $driver_trips;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'user')]
    private Collection $bookings;

    /**
     * @var Collection<int, IncidentReport>
     */
    #[ORM\OneToMany(targetEntity: IncidentReport::class, mappedBy: 'reporter')]
    private Collection $incidentReports;

    /**
     * @var Collection<int, CreditTransaction>
     */
    #[ORM\OneToMany(targetEntity: CreditTransaction::class, mappedBy: 'user')]
    private Collection $creditTransactions;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'writer')]
    private Collection $writer_reviews;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'driver')]
    private Collection $driver_reviews;

    /**
     * @var Collection<int, Preference>
     */
    #[ORM\OneToMany(targetEntity: Preference::class, mappedBy: 'user')]
    private Collection $preferences;


    public function __construct()
    {
        $this->roles=['ROLE_USER'];
        $this->credit = 20;
        $this->is_passenger = true;
        $this->is_driver = false;
        $this->is_suspended = false;
        $this->photo_url = '/uploads/users/default-profile.png';
        $this->vehicles = new ArrayCollection();
        $this->driver_trips = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->incidentReports = new ArrayCollection();
        $this->creditTransactions = new ArrayCollection();
        $this->writer_reviews = new ArrayCollection();
        $this->driver_reviews = new ArrayCollection();
        $this->preferences = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
    return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDateBirth(): ?\DateTimeInterface
    {
        return $this->date_birth;
    }

    public function setDateBirth(?\DateTimeInterface $date_birth): static
    {
        $this->date_birth = $date_birth;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(string $photo_url): static
    {
        $this->photo_url = $photo_url;

        return $this;
    }

    public function isPassenger(): ?bool
    {
        return $this->is_passenger;
    }

    public function setIsPassenger(bool $is_passenger): static
    {
        $this->is_passenger = $is_passenger;

        return $this;
    }

    public function isDriver(): ?bool
    {
        return $this->is_driver === true;
    }

    public function setIsDriver(bool $is_driver): static
    {
        $this->is_driver = $is_driver;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function addCredits(int $amount): void
    {
        $this->credit += $amount;
    }

    public function removeCredits(int $amount): void
    {
        $this->credit -= $amount;
    }

    public function isSuspended(): ?bool
    {
        return $this->is_suspended ;
    }

    public function setIsSuspended(bool $is_suspended): static
    {
        $this->is_suspended = $is_suspended;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setUser($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getUser() === $this) {
                $vehicle->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getDriverTrips(): Collection
    {
        return $this->driver_trips;
    }

    public function addDriverTrip(Trip $driverTrip): static
    {
        if (!$this->driver_trips->contains($driverTrip)) {
            $this->driver_trips->add($driverTrip);
            $driverTrip->setDriver($this);
        }

        return $this;
    }

    public function removeDriverTrip(Trip $driverTrip): static
    {
        if ($this->driver_trips->removeElement($driverTrip)) {
            // set the owning side to null (unless already changed)
            if ($driverTrip->getDriver() === $this) {
                $driverTrip->setDriver(null);
            }
        }

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
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
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
            $incidentReport->setReporter($this);
        }

        return $this;
    }

    public function removeIncidentReport(IncidentReport $incidentReport): static
    {
        if ($this->incidentReports->removeElement($incidentReport)) {
            // set the owning side to null (unless already changed)
            if ($incidentReport->getReporter() === $this) {
                $incidentReport->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CreditTransaction>
     */
    public function getCreditTransactions(): Collection
    {
        return $this->creditTransactions;
    }

    public function addCreditTransaction(CreditTransaction $creditTransaction): static
    {
        if (!$this->creditTransactions->contains($creditTransaction)) {
            $this->creditTransactions->add($creditTransaction);
            $creditTransaction->setUser($this);
        }

        return $this;
    }

    public function removeCreditTransaction(CreditTransaction $creditTransaction): static
    {
        if ($this->creditTransactions->removeElement($creditTransaction)) {
            // set the owning side to null (unless already changed)
            if ($creditTransaction->getUser() === $this) {
                $creditTransaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getWriterReviews(): Collection
    {
        return $this->writer_reviews;
    }

    public function addWriterReview(Review $review): static
    {
        if (!$this->writer_reviews->contains($review)) {
            $this->writer_reviews->add($review);
            $review->setWriter($this);
        }

        return $this;
    }

    public function removeWriterReview(Review $review): static
    {
        if ($this->writer_reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getWriter() === $this) {
                $review->setWriter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getDriverReviews(): Collection
    {
        return $this->driver_reviews;
    }

    public function addDriverReview(Review $driverReview): static
    {
        if (!$this->driver_reviews->contains($driverReview)) {
            $this->driver_reviews->add($driverReview);
            $driverReview->setDriver($this);
        }

        return $this;
    }

    public function removeDriverReview(Review $driverReview): static
    {
        if ($this->driver_reviews->removeElement($driverReview)) {
            // set the owning side to null (unless already changed)
            if ($driverReview->getDriver() === $this) {
                $driverReview->setDriver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Preference>
     */
    public function getPreferences(): Collection
    {
        return $this->preferences;
    }

    public function addPreference(Preference $preference): static
    {
        if (!$this->preferences->contains($preference)) {
            $this->preferences->add($preference);
            $preference->setUser($this);
        }

        return $this;
    }

    public function removePreference(Preference $preference): static
    {
        if ($this->preferences->removeElement($preference)) {
            // set the owning side to null (unless already changed)
            if ($preference->getUser() === $this) {
                $preference->setUser(null);
            }
        }

        return $this;
    }


    public function isProfilComplet(): bool
    {
        return !empty($this->surname)
        && !empty($this->firstname)
        && !empty($this->date_birth)
        && !empty($this->phone);
    }

}
