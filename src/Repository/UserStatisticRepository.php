<?php

namespace App\Repository;

use App\Entity\User;
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

    /**
     * @return UserStatisticRepository[] Returns an array of UserStatisticRepository objects
    */
    public function findTodaysStatisticsByUser(User $user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.date = CURRENT_DATE()')
            ->andWhere('s.user = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return UserStatisticRepository[] Returns an array of UserStatisticRepository objects
    */
    public function findStatisticsForUserByIds(User $user, array $ids)
    {
        return $this->createQueryBuilder('s')
            ->select('s.date','s.followerCount','s.followingCount')
            ->andWhere('s.id in (:list)')
            ->andWhere('s.user = :user')
            ->setParameter('user', $user->getId())
            ->setParameter('list', $ids)
            ->orderBy('s.date','asc')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
