<?php

namespace App\Entity;

use App\Enum\EnergyType;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ORM\Table(name: 'vehicle', indexes: [
    new ORM\Index(columns: ['user_id'])
])]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    #[ORM\Column(length: 20, unique:true)]
    private ?string $plate = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $brand = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $model = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $color = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $first_registration = null;

    #[Assert\NotNull]
    #[ORM\Column(enumType: EnergyType::class)]
    private ?EnergyType $energy_type = null;

    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 12)]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seats_total = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?User $user = null;

    /**
     * @var Collection<int, Trip>
     */
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'vehicle')]
    private Collection $trips;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): static
    {
        $this->plate = $plate;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getFirstRegistration(): ?\DateTimeInterface
    {
        return $this->first_registration;
    }

    public function setFirstRegistration(\DateTimeInterface $first_registration): static
    {
        $this->first_registration = $first_registration;

        return $this;
    }

    public function getEnergyType(): ?EnergyType
    {
        return $this->energy_type;
    }

    public function setEnergyType(EnergyType $energy_type): static
    {
        $this->energy_type = $energy_type;

        return $this;
    }

    public function getSeatsTotal(): ?int
    {
        return $this->seats_total;
    }

    public function setSeatsTotal(int $seats_total): static
    {
        $this->seats_total = $seats_total;

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

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setVehicle($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getVehicle() === $this) {
                $trip->setVehicle(null);
            }
        }

        return $this;
    }
}
