<?php

namespace App\Repository;

use App\Entity\SpotifyTrackHistory;
use App\Entity\User;
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

    /**
     * @return SpotifyTrackHistory[] Returns an array of UserAction objects
    */
    public function findActivitesByUser(User $user, $maxResults = 10)
    {
        return $this->createQueryBuilder('h')
            ->where('h.user = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('h.timestamp', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }
}
