<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    //requéte préparé pour compter le nombre de covoiturage réalisé . réservation ou le status est validé ou rejeté
    public function countRealizedByDay(): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT DATE(created_at) AS jour, COUNT(*) AS total
        FROM booking
        WHERE feedback_status IN ('validated', 'rejected')
        GROUP BY jour
        ORDER BY jour ASC
    ";

    $stmt = $conn->prepare($sql);
    return $stmt->executeQuery()->fetchAllAssociative();
}

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
