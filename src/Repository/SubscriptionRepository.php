<?php

namespace App\Repository;

use App\Entity\Apartment;
use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

//    /**
//     * @return Subscription[] Returns an array of Subscription objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Subscription
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function existsForApartmentAndYear(
    Apartment $apartment,
    int $year
): bool {
    return (bool) $this->createQueryBuilder('c')
        ->select('1')
        ->andWhere('c.apartment = :apartment')
        ->andWhere('c.year = :year')
        ->setParameter('apartment', $apartment)
        ->setParameter('year', $year)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
}



}
