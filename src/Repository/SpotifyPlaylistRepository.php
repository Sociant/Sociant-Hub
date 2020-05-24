<?php

namespace App\Repository;

use App\Entity\SpotifyPlaylist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpotifyPlaylist|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpotifyPlaylist|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpotifyPlaylist[]    findAll()
 * @method SpotifyPlaylist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpotifyPlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpotifyPlaylist::class);
    }

    // /**
    //  * @return SpotifyPlaylist[] Returns an array of SpotifyPlaylist objects
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
    public function findOneBySomeField($value): ?SpotifyPlaylist
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
