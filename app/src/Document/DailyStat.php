<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class DailyStat
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'date')]
    private ?\DateTimeImmutable $date = null;

    #[MongoDB\Field(type: 'int')]
    private ?int $newUsers = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getNewUsers(): ?int
    {
        return $this->newUsers;
    }

    public function setNewUsers(int $newUsers): static
    {
        $this->newUsers = $newUsers;
        return $this;
    }
}
