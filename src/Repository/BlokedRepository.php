<?php

namespace App\Repository;

use App\Entity\Bloked;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Bloked|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bloked|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bloked[]    findAll()
 * @method Bloked[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlokedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bloked::class);
    }

    // /**
    //  * @return Bloked[] Returns an array of Bloked objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bloked
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}