<?php

namespace App\Repository;

use App\Entity\AutomatedUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AutomatedUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutomatedUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutomatedUpdate[]    findAll()
 * @method AutomatedUpdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutomatedUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutomatedUpdate::class);
    }

    // /**
    //  * @return AutomatedUpdate[] Returns an array of AutomatedUpdate objects
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
    public function findOneBySomeField($value): ?AutomatedUpdate
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
