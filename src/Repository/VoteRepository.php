<?php

namespace App\Repository;

use App\Entity\Boutique;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function findVoteWithHeaderBy(Boutique $boutique){
        return $this->createQueryBuilder('v')
                    ->select('v','h')
                    ->leftJoin('v.header_vote','h')
                    ->where('v.boutique = :boutique')
                    ->setParameter('boutique',$boutique)
                    ->getQuery()
                    ->getResult()
                    ;
    }
    
    public function findAllWithUserVote()
    {
        return $this->createQueryBuilder('v')
                    ->select('v','u')
                    ->leftJoin('v.userVotes','u')
                    ->orderBy('v.placement',"DESC")
                    ->getQuery()
                    ->getResult()
                    ;
    
    }
    public function findOnWithUserVote(int $id_vote)
    {
        return $this->createQueryBuilder('v')
                    ->select('v','u')
                    ->leftJoin('v.userVotes','u')
                    ->orderBy('v.placement',"DESC")
                    ->where('v.id = :id')
                    ->setParameter('id',$id_vote)
                    ->getQuery()
                    ->getOneOrNullResult()
                    ;
        
    }
    // /**
    //  * @return Vote[] Returns an array of Vote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vote
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
