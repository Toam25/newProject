<?php

namespace App\Repository;

use App\Entity\EsArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EsArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method EsArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method EsArticle[]    findAll()
 * @method EsArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EsArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EsArticle::class);
    }

    // /**
    //  * @return EsArticle[] Returns an array of EsArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EsArticle
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
