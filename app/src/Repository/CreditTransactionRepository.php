<?php

namespace App\Repository;

use App\Entity\CreditTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CreditTransaction>
 */
class CreditTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditTransaction::class);
    }

    public function sumPlatformWin(): int
    {
    $qb = $this->createQueryBuilder('ct');

    $totalFees = (int) $qb
        ->select('SUM(ct.amount)')
        ->where('ct.type = :fee')
        ->setParameter('fee', 'plateform_fee')
        ->getQuery()
        ->getSingleScalarResult();


    return $totalFees;
    }

    public function creditsByDay(): array
    {
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT DATE(created_at) AS jour,
            SUM(CASE WHEN type = 'plateform_fee' THEN amount ELSE 0 END) AS total
        FROM credit_transaction
        GROUP BY jour
        ORDER BY jour ASC
    ";

    $stmt = $conn->prepare($sql);
    return $stmt->executeQuery()->fetchAllAssociative();
    }

//    /**
//     * @return CreditTransaction[] Returns an array of CreditTransaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CreditTransaction
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
