<?php


namespace App\Repository;

use App\Entity\Useractivitylog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Useractivitylog::class);
    }

    // You can add custom repository methods here
}
