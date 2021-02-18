<?php

namespace App\Repository;

use App\Data\Search;
use App\Entity\Article;
use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


    public function getArticleWithVote()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a', 'v')
            ->leftjoin('a.votes', 'v');
        return $query->getQuery()->getResult();
    }
    public function getArticleWithVoteBy(Search $data)
    {

        $query = $this->createQueryBuilder('a')
            ->select('a', 'b', 'v', 'i', 'c')
            ->leftJoin('a.votes', 'v')
            ->leftJoin('a.boutique', 'b')
            ->leftJoin('a.images', 'i')
            ->leftJoin('a.carts', 'c');
        if ($data->q) {
            $query->andWhere('a.name LIKE :q')
                ->orWhere('a.marque LIKE :q')
                ->orWhere('a.category LIKE :q')
                ->orWhere('a.wordKey LIKE :q')
                ->orWhere('a.description LIKE :q')
                ->setParameter('q', '%' . $data->q . '%');
        }
        if ($data->category) {
            $query->andWhere('a.category IN (:category)')
                ->setParameter('category', $data->category);
        }
        if ($data->marque) {
            $query->andWhere('a.marque IN (:marque)')
                ->setParameter('marque', $data->marque);
        }
        if ($data->minPrice) {
            $query->andWhere('a.price >= :minprice')
                ->setParameter('minprice', $data->minPrice);
        }
        if ($data->maxPrice) {
            $query->andWhere('a.price <= :maxprice')
                ->setParameter('maxprice', $data->maxPrice);
        }
        if ($data->boutique_id) {
            $query->andWhere('b.id = :boutiqueId')
                ->setParameter('boutiqueId', $data->boutique_id);
        }
        if(isset($data->type)) {
            $query->andWhere('a.type In (:type)')
                ->setParameter('type', $data->type);
        }

        return $query->getQuery()->getResult();
    }

    public function findAllArticleByBoutique(?Boutique $boutique)
    {

        if ($boutique != null) {
            return $this->createQueryBuilder('a')
                ->select('a', 'b', 'v', 'i', 'c')
                ->leftJoin('a.votes', 'v')
                ->leftJoin('a.boutique', 'b')
                ->leftJoin('a.images', 'i')
                ->leftJoin('a.carts', 'c')
                ->andwhere('a.boutique = :boutiqueid')
                ->setParameter('boutiqueid', $boutique->getId())
                ->getQuery()
                ->getResult();
        }
        return [];
    }
    public function findOneArticleByBoutiqueWithImage(int $id, Boutique $boutique=null){
        $query= $this->createQueryBuilder('a')
        ->select('a', 'b', 'v', 'i', 'c','u')
        ->leftJoin('a.votes', 'v')
        ->leftJoin('v.user', 'u')
        ->leftJoin('a.boutique', 'b')
        ->leftJoin('a.images', 'i')
        ->leftJoin('a.carts', 'c')
        ->andwhere('a.id = :id')
        ->setParameter('id', $id);
    if($boutique != null){
       $query->andwhere('a.boutique = :boutiqueid')
            ->setParameter('boutiqueid', $boutique->getId());
    }
        
    return  $query ->getQuery()->getOneOrNullResult();
}
public function findAllArticleBySousCategory(string $category, string $sous_category){
    $query= $this->createQueryBuilder('a')
    ->select('a', 'b', 'v', 'i', 'c','u')
    ->leftJoin('a.votes', 'v')
    ->leftJoin('v.user', 'u')
    ->leftJoin('a.boutique', 'b')
    ->leftJoin('a.images', 'i')
    ->leftJoin('a.carts', 'c')
    ->andwhere('a.sous_category = :sous_category')
    ->andwhere('a.category = :category')
    ->setParameter('sous_category', $sous_category)
    ->setParameter('category', $category);
    return  $query ->getQuery()->getResult();
}

public function findAllArticleSliderByBoutique($boutique){
     $query= $this->createQueryBuilder('a')
    ->select('a', 'b', 'v', 'i', 'c','u')
    ->leftJoin('a.votes', 'v')
    ->leftJoin('v.user', 'u')
    ->leftJoin('a.boutique', 'b')
    ->leftJoin('a.images', 'i')
    ->leftJoin('a.carts', 'c')
    ->andwhere('a.boutique = :boutiqueId')
    ->andwhere('a.slide = :slide')
    ->setParameter('boutiqueId', $boutique->getId())
    ->setParameter('slide', 1);
    return  $query ->getQuery()->getResult();
}




    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
