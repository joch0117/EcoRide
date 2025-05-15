<?php

namespace App\Service;

use App\Entity\Trip;
use App\Repository\TripRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class TripSearchService
{
    function __construct(
        private TripRepository $tripRepo,
        private EntityManagerInterface $em
    ){}

    public function searchTrip(
        ?string $departureCity,
        ?string $arrivalCity,
        ?DateTimeInterface $date,
    ): array{
        $qb = $this->em->createQueryBuilder()
        ->select('t')
        ->from(Trip::class, 't');

    if ($departureCity) {
        $qb->andWhere('t.departure_city = :departureCity')
            ->setParameter('departureCity', $departureCity);
    }

    if ($arrivalCity) {
        $qb->andWhere('t.arrival_city = :arrivalCity')
            ->setParameter('arrivalCity', $arrivalCity);
    }

    if ($date) {
        // Recherche stricte
        $start = (new \DateTime($date->format('Y-m-d')))->setTime(0, 0, 0);
        $end = (new \DateTime($date->format('Y-m-d')))->setTime(23, 59, 59);

        $qbDate = clone $qb;
        $qbDate->andWhere('t.departure_datetime BETWEEN :start AND :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end);

        $results = $qbDate->getQuery()->getResult();

        if (count($results) === 0) {
            // Recherche élargie
            $wideStart = (new \DateTime($date->format('Y-m-d')))->modify('-2 days')->setTime(0, 0, 0);
            $wideEnd = (new \DateTime($date->format('Y-m-d')))->modify('+2 days')->setTime(23, 59, 59);

            $qbWide = clone $qb;
            $qbWide->andWhere('t.departure_datetime BETWEEN :startWide AND :endWide')
                    ->setParameter('startWide', $wideStart)
                    ->setParameter('endWide', $wideEnd);

            return $qbWide->getQuery()->getResult();
        }

        return $results;
    }

    return $qb->getQuery()->getResult();
}
}