<?php


namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

// Your code that uses ResultSetMapping


class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
    public function search($searchTerm = null, $date = null)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $queryBuilder->where('1 = 1'); // Placeholder condition to ensure the WHERE clause is always present

        if ($searchTerm !== null) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('e.name', ':searchTerm'),
                    $queryBuilder->expr()->eq('e.price', ':searchTerm'),
                    $queryBuilder->expr()->eq('e.nbrparticipants', ':searchTerm')
                )
            )->setParameter('searchTerm', $searchTerm);
        }

        if ($date !== null) {
            $queryBuilder->andWhere('e.datedeb <= :date')
                ->andWhere('e.datefin >= :date')
                ->setParameter('date', $date);
        }

        return $queryBuilder->getQuery()->getResult();
    }





    public function getEventData(): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('nbrParticipants', 'nbrParticipants');
        $rsm->addScalarResult('count', 'count');

        $query = $this->getEntityManager()->createNativeQuery('
        SELECT name, nbrParticipants, COUNT(*) as count
        FROM event
        GROUP BY name, nbrParticipants
    ', $rsm);

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countLikedEventsByUserId(int $userId): int
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT COUNT(le.idUser) FROM App\Entity\LikedEvent le WHERE le.idUser = :userId'
        );
        $query->setParameter('userId', $userId);

        return $query->getSingleScalarResult();
    }


    public function findAllOrderedByField(string $orderByField): array
    {
        $qb = $this->createQueryBuilder('e');

        // Order the events by the specified field
        switch ($orderByField) {
            case 'name':
                $qb->orderBy('e.name', 'ASC');
                break;
            case 'datedeb':
                $qb->orderBy('e.datedeb', 'ASC');
                break;
            case 'nbrparticipants':
                $qb->orderBy('e.nbrparticipants', 'ASC');
                break;
            default:
                // Default ordering
                $qb->orderBy('e.id', 'ASC');
        }

        // Execute the query and return the results
        return $qb->getQuery()->getResult();
    }

    /**
     * @throws Exception
     */
    public function insertIntoParticipationTable(EntityManagerInterface $entityManager, $idPlayerValue, $idEventValue): void
    {
        $connection = $entityManager->getConnection();
        $sql = "INSERT INTO participation (IdPlayer, IdEvent) VALUES (:idPlayer, :idEvent)";
        $statement = $connection->prepare($sql);

        // Bind values to parameters
        $statement->bindValue('idPlayer', $idPlayerValue);
        $statement->bindValue('idEvent', $idEventValue);

        // Execute the query
        $statement->execute();
    }
    public function filterByName($name)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }

    // You can add custom repository methods here
}
