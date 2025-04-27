<?php

namespace App\Entity;


use App\Enum\CreditTransactionType;
use App\Repository\CreditTransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CreditTransactionRepository::class)]
#[ORM\Table(name: 'credit_transaction', indexes: [
    new ORM\Index(columns: ['user_id', 'created_at'])
])]
class CreditTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: CreditTransactionType::class)]
    private ?CreditTransactionType $type = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le montant doit être strictement positif.")]
    private ?int $amount = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: "La description ne peut pas dépasser 255 caractères.")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date de création est requise.")]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'creditTransactions')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'creditTransactions')]
    private ?Trip $trip = null;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?CreditTransactionType
    {
        return $this->type;
    }

    public function setType(CreditTransactionType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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
