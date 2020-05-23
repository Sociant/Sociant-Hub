<?php

namespace App\Repository;

use App\Entity\SpotifyTrackAdditional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpotifyTrackAdditional|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpotifyTrackAdditional|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpotifyTrackAdditional[]    findAll()
 * @method SpotifyTrackAdditional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpotifyTrackAdditionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpotifyTrackAdditional::class);
    }

    // /**
    //  * @return SpotifyTrackAdditional[] Returns an array of SpotifyTrackAdditional objects
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
    public function findOneBySomeField($value): ?SpotifyTrackAdditional
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
