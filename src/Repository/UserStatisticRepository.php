<?php

namespace App\Repository;

use App\Entity\UserStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStatistic[]    findAll()
 * @method UserStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStatistic::class);
    }

    // /**
    //  * @return UserStatistic[] Returns an array of UserStatistic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserStatistic
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
