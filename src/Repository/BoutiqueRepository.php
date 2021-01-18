<?php

namespace App\Repository;

use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Boutique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boutique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boutique[]    findAll()
 * @method Boutique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boutique::class);
    }
    public function findOneByWithHeaderReference(string $type,int $id){
        return $this->createQueryBuilder('b')
                    ->select('b','h','r')
                    ->leftJoin('b.headers', 'h')
                    ->leftJoin('b.shopReferences', 'r')
                    ->where('b.type = :type')
                    ->andWhere('b.id = :id')
                    ->setParameters([
                        'type'=>$type,
                        'id'=>$id
                    ])
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }
    public function findOneBoutiqueByUserPerRole(String $role){
        return $this->createQueryBuilder('b')
                    ->select('b','u')
                    ->join('b.user', 'u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%'.$role.'%')
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }
    // /**
    //  * @return Boutique[] Returns an array of Boutique objects
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
    public function findOneBySomeField($value): ?Boutique
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
