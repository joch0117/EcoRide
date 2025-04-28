<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findBySearch(?string $departureCity, ?string $arrivalCity, ?\DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($departureCity) {
            $qb->andWhere('t.departure_city LIKE :departureCity')
                ->setParameter('departureCity', '%' . $departureCity . '%');
        }

        if ($arrivalCity) {
            $qb->andWhere('t.arrival_city LIKE :arrivalCity')
                ->setParameter('arrivalCity', '%' . $arrivalCity . '%');
        }

        if ($date) {
            $qb->andWhere('DATE(t.departure_datetime) = :departureDate')
                ->setParameter('departureDate', $date->format('Y-m-d'));
        }

        return $qb
            ->orderBy('t.departure_datetime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
