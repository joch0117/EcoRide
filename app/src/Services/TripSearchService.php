<?php

namespace App\Services;

use App\Entity\Trip;
use App\Repository\TripRepository;
use DateTimeInterface;

class TripSearchService
{
    function __construct(
        private TripRepository $tripRepo
    ){}

    public function searchTrip(
        ?string $departureCity,
        ?string $arrivalCity,
        ?DateTimeInterface $date,
        array $filters = []
    ): array{
        return $this->tripRepo->findBySearch($departureCity,$arrivalCity,$date,$filters);
    }
}