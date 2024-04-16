<?php


namespace App\Repository;

use App\Entity\Likedevent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LikedEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likedevent::class);
    }

    // You can add custom repository methods here
}
