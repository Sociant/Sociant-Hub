<?php

namespace App\Repository;

use App\Entity\UserAnalytics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAnalytics|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAnalytics|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAnalytics[]    findAll()
 * @method UserAnalytics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAnalyticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAnalytics::class);
    }
}
