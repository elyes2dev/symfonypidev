<?php


namespace App\Repository;

use App\Entity\Claim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClaimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Claim::class);
    }

    public function countClaimsByType(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.type, COUNT(c.id) as claimCount')
            ->groupBy('c.type');

        $results = $qb->getQuery()->getResult();

        $claimsByType = [];
        foreach ($results as $result) {
            $claimsByType[$result['type']] = $result['claimCount'];
        }

        return $claimsByType;
    }
}
