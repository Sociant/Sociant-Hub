<?php

namespace App\Repository;

use App\Entity\AutomatedUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AutomatedUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutomatedUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutomatedUpdate[]    findAll()
 * @method AutomatedUpdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutomatedUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutomatedUpdate::class);
    }
}
