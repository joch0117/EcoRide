<?php

namespace App\Entity;

use App\Enum\StateChecked;
use App\Repository\IncidentReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IncidentReportRepository::class)]
#[ORM\Table(name: 'incident_report', indexes: [
    new ORM\Index(columns: ['trip_id']),
    new ORM\Index(columns: ['reporter_id']),
    new ORM\Index(columns: ['incident_status'])
])]
class IncidentReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    #[ORM\Column(length: 250)]
    private ?string $description = null;

    #[ORM\Column(enumType: StateChecked::class)]
    private ?StateChecked $incident_status = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'incidentReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    #[ORM\ManyToOne(inversedBy: 'incidentReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reporter = null;

        public function __construct()
    {
    $this->created_at = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIncidentStatus(): ?StateChecked
    {
        return $this->incident_status;
    }

    public function setIncidentStatus(StateChecked $incident_status): static
    {
        $this->incident_status = $incident_status;

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

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): static
    {
        $this->reporter = $reporter;

        return $this;
    }
}
