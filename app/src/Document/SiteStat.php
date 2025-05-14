<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class SiteStat
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: "date")]
    private \DateTime $date;

    #[MongoDB\Field(type: "int")]
    private int $nbTrajets;

    #[MongoDB\Field(type: "int")]
    private int $creditsGagnes;

    #[MongoDB\Field(type: "int")]
    private int $nbUtilisateurs;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->nbTrajets = 0;
        $this->creditsGagnes = 0;
        $this->nbUtilisateurs = 0;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getNbTrajets(): int
    {
        return $this->nbTrajets;
    }

    public function setNbTrajets(int $nbTrajets): void
    {
        $this->nbTrajets = $nbTrajets;
    }

    public function getCreditsGagnes(): int
    {
        return $this->creditsGagnes;
    }

    public function setCreditsGagnes(int $creditsGagnes): void
    {
        $this->creditsGagnes = $creditsGagnes;
    }

    public function getNbUtilisateurs(): int
    {
        return $this->nbUtilisateurs;
    }

    public function setNbUtilisateurs(int $nbUtilisateurs): void
    {
        $this->nbUtilisateurs = $nbUtilisateurs;
    }
}
