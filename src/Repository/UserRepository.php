<?php


namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

     // Method to find all users
     public function findAllUsers()
     {
         return $this->createQueryBuilder('u')
             ->getQuery()
             ->getResult();
     }

     // Method to find a user by ID
    public function findById(int $id)
    {
        return $this->find($id);
    }

    // Method to save a user entity
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // Method to delete a user entity
    public function delete(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
   // Method to find a single user by email criteria
public function findByEmail(String $email)
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.email = :email')
        ->setParameter('email', $email)
        ->getQuery()
        ->getOneOrNullResult();
}

// Method to find users by role criteria
public function findByRole(string $role)
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.role = :role')
        ->setParameter('role', $role)
        ->getQuery()
        ->getResult();
}

/** baed bch nzid des requete specifique kima el count , findby .....  */


}
