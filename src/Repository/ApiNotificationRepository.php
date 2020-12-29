<?php

namespace App\Repository;

use App\Entity\ApiNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiNotification[]    findAll()
 * @method ApiNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiNotification::class);
    }

    // /**
    //  * @return ApiNotification[] Returns an array of ApiNotification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiNotification
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
