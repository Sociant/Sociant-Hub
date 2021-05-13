<?php

namespace App\Repository;

use App\Entity\BannedUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BannedUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method BannedUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method BannedUser[]    findAll()
 * @method BannedUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannedUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BannedUser::class);
    }

    // /**
    //  * @return BannedUser[] Returns an array of BannedUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BannedUser
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
