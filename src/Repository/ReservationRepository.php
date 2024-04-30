<?php


namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // Repository method to fetch reservation statistics by month
    public function getMonthlyReservationStatistics(): array
    {
        return $this->createQueryBuilder('r')
            ->select("SUBSTRING(r.date, 1, 7) AS monthYear, COUNT(r.id) AS count")
            ->groupBy("monthYear")  
            ->getQuery()
            ->getResult();
    }
    

}
