<?php

namespace App\Repository;

use App\Entity\User;
use app\Enum\StatusReview;
use App\Entity\Review;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    // src/Repository/ReviewRepository.php
    public function getAverageForDriver(User $driver): ?float
    {
    return (float) $this->createQueryBuilder('r')
        ->select('AVG(r.rating)')
        ->where('r.driver = :driver')
        ->andWhere('r.status = :status')
        ->setParameter('driver', $driver)
        ->setParameter('status', StatusReview::APPROVED)
        ->getQuery()
        ->getSingleScalarResult();
    }


    public function hasReview(User $user, Trip $trip): bool
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.writer = :user')
            ->andWhere('r.trip = :trip')
            ->setParameter('user', $user)
            ->setParameter('trip', $trip)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

//    /**
//     * @return Review[] Returns an array of Review objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Review
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
