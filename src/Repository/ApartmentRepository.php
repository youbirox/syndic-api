<?php

namespace App\Repository;

use App\Entity\Apartment;
use App\Entity\Residence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Apartment>
 */
class ApartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apartment::class);
    }

//    /**
//     * @return Apartment[] Returns an array of Apartment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Apartment
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByResidence(Residence $residence): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.building', 'b')
            ->andWhere('b.residence = :residence')
            ->setParameter('residence', $residence)
            ->getQuery()
            ->getResult();
    }

    public function findAvailableByResidence(Residence $residence): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.building', 'b')
            ->where('b.residence = :residence')
            ->andWhere('a.resident IS NULL')
            ->setParameter('residence', $residence)
            ->getQuery()
            ->getResult();
    }
}
