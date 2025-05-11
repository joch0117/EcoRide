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
        
        $qb->orderBy('t.departure_datetime', 'ASC');
    
        return $qb->getQuery()->getResult();
    }
}