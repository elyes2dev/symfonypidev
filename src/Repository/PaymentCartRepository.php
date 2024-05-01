<?php

namespace App\Repository;

use App\Entity\PaymentCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentCart>
 *
 * @method PaymentCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentCart[]    findAll()
 * @method PaymentCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentCart::class);
    }

//    /**
//     * @return PaymentCart[] Returns an array of PaymentCart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PaymentCart
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
