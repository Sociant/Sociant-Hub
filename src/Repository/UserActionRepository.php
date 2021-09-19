<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAction[]    findAll()
 * @method UserAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAction::class);
    }

    /**
     * @return UserAction[] Returns an array of UserAction objects
     */
    public function findActivitiesByUser(User $user, $maxResults = 10, $page = 0, $increaseForMoreAvailable = false)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin("a.twitterUser","tu")
            ->where('a.user = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('a.id', 'DESC')
            ->setFirstResult($maxResults * $page)
            ->setMaxResults($increaseForMoreAvailable ? ($maxResults + 1) : $maxResults)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return UserAction[] Returns an array of UserAction objects
     */
    public function findAllActivitiesByUser(User $user)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin("a.twitterUser","tu")
            ->where('a.user = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
