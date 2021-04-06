<?php

namespace App\Repository;

use App\Entity\UserVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVote[]    findAll()
 * @method UserVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVote::class);
    }

    // /**
    //  * @return UserVote[] Returns an array of UserVote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserVote
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
