<?php

namespace App\Repository;

use App\Entity\SpotifyPlaylistAnalysis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpotifyPlaylistAnalysis|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpotifyPlaylistAnalysis|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpotifyPlaylistAnalysis[]    findAll()
 * @method SpotifyPlaylistAnalysis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpotifyPlaylistAnalysisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpotifyPlaylistAnalysis::class);
    }

    // /**
    //  * @return SpotifyPlaylistAnalysis[] Returns an array of SpotifyPlaylistAnalysis objects
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
    public function findOneBySomeField($value): ?SpotifyPlaylistAnalysis
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
