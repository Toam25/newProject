<?php

namespace App\Repository;

use App\Entity\UserCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCondition[]    findAll()
 * @method UserCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCondition::class);
    }

    // /**
    //  * @return UserCondition[] Returns an array of UserCondition objects
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
    public function findOneBySomeField($value): ?UserCondition
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
