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
    
        if ($date instanceof \DateTimeInterface) {
            $start = (new \DateTime())->setTimestamp($date->getTimestamp())->setTime(0, 0, 0);
            $end = (new \DateTime())->setTimestamp($date->getTimestamp())->setTime(23, 59, 59);
            $qb->andWhere('t.departure_datetime BETWEEN :start AND :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end);
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
        
        $qb->orderBy('t.departure_datetime', 'ASC');
    
        return $qb->getQuery()->getResult();
    }
}