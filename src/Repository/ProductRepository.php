<?php


namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // You can add custom repository methods here

    public function searchByName($query)
    {
    return $this->createQueryBuilder('s')
        ->andWhere('s.name LIKE :query')
        ->setParameter('query', $query.'%')
        ->orderBy('s.name', 'ASC')
        ->getQuery()
        ->getResult();
    }
}
