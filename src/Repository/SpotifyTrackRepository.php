<?php

namespace App\Repository;

use App\Entity\SpotifyTrack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpotifyTrack|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpotifyTrack|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpotifyTrack[]    findAll()
 * @method SpotifyTrack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpotifyTrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpotifyTrack::class);
    }

    // /**
    //  * @return SpotifyTrack[] Returns an array of SpotifyTrack objects
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
    public function findOneBySomeField($value): ?SpotifyTrack
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
