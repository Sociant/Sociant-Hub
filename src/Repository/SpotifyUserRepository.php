<?php

namespace App\Repository;

use App\Entity\SpotifyUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpotifyUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpotifyUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpotifyUser[]    findAll()
 * @method SpotifyUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpotifyUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpotifyUser::class);
    }

    // /**
    //  * @return SpotifyUser[] Returns an array of SpotifyUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpotifyUser
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
