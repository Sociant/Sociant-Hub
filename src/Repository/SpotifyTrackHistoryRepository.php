<?php

namespace App\Repository;

use App\Entity\SpotifyTrackHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpotifyTrackHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpotifyTrackHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpotifyTrackHistory[]    findAll()
 * @method SpotifyTrackHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpotifyTrackHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpotifyTrackHistory::class);
    }

    // /**
    //  * @return SpotifyTrackHistory[] Returns an array of SpotifyTrackHistory objects
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
    public function findOneBySomeField($value): ?SpotifyTrackHistory
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
