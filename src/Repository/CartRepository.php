<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Cart;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }
   
    public function findOneByArticleWithUser(User $user, Article $article, string $type){
  
        $query = $this->createQueryBuilder('c')
                ->select('c','a','u')
                ->leftjoin('c.articles','a')
                ->leftJoin('c.user', 'u')
                ->andWhere('a.id = :article')
                ->andWhere('u.id = :user')
                ->andWhere('c.type = :type')
                ->setParameter('article',$article->getId())
                ->setParameter('user',$user->getId())
                ->setParameter('type',$type)
                ->getQuery()
                ->getOneOrNullResult()
                 
        ;
      return $query;
    }
    public function findOneByIdCartWithUser(User $user,int $id){
        $query = $this->createQueryBuilder('c')
                ->select('c','a','u')
                ->leftJoin('c.user', 'u')
                ->leftJoin('c.articles', 'a')
                ->andWhere('u.id = :user')
                ->andWhere('c.id = :id')
                ->setParameter('id',$id)
                ->setParameter('user',$user->getId())
                ->getQuery()
                ->getOneOrNullResult()
                 
        ;
      return $query;
    }
    public function findAllByUser(User $user,string $status){
        $query = $this->createQueryBuilder('c')
                ->select('c','a','u','i','b')
                ->leftjoin('c.articles','a')
                ->leftJoin('c.user', 'u')
                ->leftJoin('a.images', 'i')
                ->leftJoin('u.boutiques', 'b')
                ->andWhere('u.id = :user')
                ->andWhere('c.status = :type')
                ->setParameters([
                  'user'=>$user->getId(),
                  'type'=>$status
                  ])
               
                ->getQuery()
                ->getResult()
                 
        ;
      return $query;
    }
    public function findAllByUserAndPanier(User $user,string $status){
        $query = $this->createQueryBuilder('c')
                ->select('c','a','u','i','b')
                ->leftjoin('c.articles','a')
                ->leftJoin('c.user', 'u')
                ->leftJoin('a.images', 'i')
                ->leftJoin('u.boutiques', 'b')
                ->andWhere('u.id = :user')
                ->andWhere('c.type = :type')
                ->andWhere('c.status = :status')
                ->setParameter('user',$user->getId())
                ->setParameter('type','cart')
                ->setParameter('status',$status)
                ->getQuery()
                ->getResult()
                 
        ;
      return $query;
    }
    public function findAllByUserAndWish(User $user,string $status){
      $query = $this->createQueryBuilder('c')
              ->select('c','a','u','i','b')
              ->leftjoin('c.articles','a')
              ->leftJoin('c.user', 'u')
              ->leftJoin('a.images', 'i')
              ->leftJoin('u.boutiques', 'b')
              ->andWhere('u.id = :user')
              ->andWhere('c.type = :type')
              ->andWhere('c.status = :status')
              ->setParameter('user',$user->getId())
              ->setParameter('type','wish')
              ->setParameter('status',$status)
              ->getQuery()
              ->getResult()
               
      ;
    return $query;
  }

  public function findOneByIdArticleWithUser(User $user,string $status,int $idArticle){
 
              $query = $this->createQueryBuilder('c')
              ->select('c','a','u')
              ->leftjoin('c.articles','a')
              ->leftJoin('c.user', 'u')
              ->andWhere('u.id = :user')
              ->andWhere('a.id = :idarticle')
              ->andWhere('c.status = :status')
              ->setParameters([
                'user'=>$user->getId(),
                'idarticle'=>$idArticle,
                'status' => $status
                ])
            
              ->getQuery()
              ->getOneOrNullResult()
              
          ;
          return $query;
  }

    // /**
    //  * @return Cart[] Returns an array of Cart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
