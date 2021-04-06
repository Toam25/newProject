<?php

namespace App\Repository;

use App\Entity\HeaderVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HeaderVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeaderVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeaderVote[]    findAll()
 * @method HeaderVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeaderVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeaderVote::class);
    }

    // /**
    //  * @return HeaderVote[] Returns an array of HeaderVote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HeaderVote
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
