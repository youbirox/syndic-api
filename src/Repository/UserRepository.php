<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
    * Retourne tous les utilisateurs avec le rôle ROLE_RESIDENT
    * pour une résidence spécifique
    *
    * @param int|object $residence La résidence ou son id
    * @return User[]
    */
    public function findResidentsByResidence($residence)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.apartment', 'a')      
            ->addSelect('a')                    
            ->where('u.residence = :residence')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('residence', $residence)
            ->setParameter('role', '%ROLE_RESIDENT%');

    return $qb->getQuery()->getResult();
    }

    public function countResidentsByResidence($residence): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.residence = :residence')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('residence', $residence)
            ->setParameter('role', '%ROLE_RESIDENT%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countResidentsInactif($residence): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.residence = :residence')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('u.actif LIKE :actif')
            ->setParameter('residence', $residence)
            ->setParameter('role', '%ROLE_RESIDENT%')
            ->setParameter('actif', '%0%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countResidentsActif($residence): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.residence = :residence')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('u.actif LIKE :actif')
            ->setParameter('residence', $residence)
            ->setParameter('role', '%ROLE_RESIDENT%')
            ->setParameter('actif', '%1%')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
