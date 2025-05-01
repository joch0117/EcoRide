<?php

namespace App\Repository;

use App\Enum\StatusReview;
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

    public function findBySearch(
        ?string $departureCity,
        ?string $arrivalCity,
        ?\DateTimeInterface $date,
        array $filters = []
    ): array {
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
    
        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('t.price <= :maxPrice')
                ->setParameter('maxPrice', $filters['maxPrice']);
        }
    
        if (!empty($filters['maxDuration'])) {
            $qb->andWhere('t.duration <= :maxDuration')
                ->setParameter('maxDuration', $filters['maxDuration']);
        }
    
        if (!empty($filters['isEcological'])) {
            $qb->andWhere('t.is_ecological = true');
        }
    
        if (!empty($filters['vehicleType'])) {
            $qb->join('t.vehicle', 'v') // jointure sur l'entité Vehicle
                ->andWhere('v.energy_type = :vehicleType')
                ->setParameter('vehicleType', $filters['vehicleType']);
        }
    
        if (!empty($filters['minRating'])) {
            $qb->andWhere('
                (SELECT AVG(r.rating) 
                FROM App\Entity\Review r 
                WHERE r.trip = t AND r.status = :validatedStatus
                ) >= :minRating
            ')
            ->setParameter('minRating', $filters['minRating'])
            ->setParameter('validatedStatus', StatusReview::APPROVED->value);
        }
        
    
        // Tri des résultats
        if (!empty($filters['sortBy'])) {
            $field = match($filters['sortBy']) {
                'price' => 't.price',
                'datetime' => 't.departure_datetime',
                'duration' => 't.duration',
                default => 't.departure_datetime',
            };
            $qb->orderBy($field, 'ASC');
        } else {
            $qb->orderBy('t.departure_datetime', 'ASC');
        }
    
        return $qb->getQuery()->getResult();
    }
}