<?php

namespace App\Repository;

use App\Data\Search;
use App\Entity\Blog;
use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function findAllBlogByBoutique(?Boutique $boutique)
    {

        if ($boutique != null) {
            return $this->createQueryBuilder('bl')
                ->select('bl', 'b', 'v')
                ->leftJoin('bl.votes', 'v')
                ->leftJoin('bl.boutique', 'b')
                ->andwhere('bl.boutique = :boutiqueid')
                ->setParameter('boutiqueid', $boutique->getId())
                ->getQuery()
                ->getResult();
        }
        return [];
    }

    public function findByNotValidateBlog(Boutique $boutique = null)
    {
        $query = $this->createQueryBuilder('bl')
            ->select('bl', 'b', 'v')
            ->leftJoin('bl.votes', 'v')
            ->leftJoin('bl.boutique', 'b')
            ->andwhere('bl.validate = :validate')
            ->orwhere('bl.validateInHomePage = :validateInHomePage')
            ->setParameters([
                'validate' => false,
                'validateInHomePage' => false
            ]);
        if ($boutique != null) {
            $query->andwhere('bl.boutique = :boutiqueid')
                ->setParameter('boutiqueid', $boutique->getId());
        }

        $query->orderBy('bl.id', 'DESC');

        return $query->getQuery()->getResult();
    }
    public function findByBlogValidateInHomePage(Boutique $boutique = null)
    {
        $query = $this->createQueryBuilder('bl')
            ->select('bl', 'b', 'v')
            ->leftJoin('bl.votes', 'v')
            ->leftJoin('bl.boutique', 'b')
            ->andwhere('bl.validateInHomePage = :validate')
            ->setParameters([
                'validate' => true
            ]);
        if ($boutique != null) {
            $query->andwhere('bl.boutique = :boutiqueid')
                ->setParameter('boutiqueid', $boutique->getId());
        }

        return $query->getQuery()->getResult();
    }

    public function findByValidate(Boutique $boutique = null)
    {
        $query = $this->createQueryBuilder('bl')
            ->select('bl', 'b', 'v', 'vi')
            ->leftJoin('bl.votes', 'v')
            ->leftJoin('bl.boutique', 'b')
            ->leftJoin('b.videos', 'vi')
            ->andwhere('bl.validate = :validate')
            ->orderBy('bl.id', 'DESC')
            ->setParameters([
                'validate' => true
            ]);
        if ($boutique != null) {
            $query->andwhere('bl.boutique = :boutiqueid')
                ->setParameter('boutiqueid', $boutique->getId());
        }

        return $query->getQuery()->getResult();
    }

    public function findByBlogValidate(Boutique $boutique = null)
    {
        $query = $this->createQueryBuilder('bl')
            ->select('bl', 'b', 'v')
            ->leftJoin('bl.votes', 'v')
            ->leftJoin('bl.boutique', 'b')
            ->andwhere('bl.validateInHomePage = :validateInHomePage')
            ->setParameters([
                'validateInHomePage' => false
            ]);
        if ($boutique != null) {
            $query->andwhere('bl.boutique = :boutiqueid')
                ->setParameter('boutiqueid', $boutique->getId());
        }

        return $query->getQuery()->getResult();
    }

    public function getAllBlogWithDataBy(Search $data, $validate = true)
    {

        $query = $this->createQueryBuilder('bl')
            ->select('bl', 'b', 'v')
            ->leftJoin('bl.votes', 'v')
            ->leftJoin('bl.boutique', 'b');
        if ($data->q) {
            $query->andWhere('bl.title LIKE :q')
                ->orWhere('bl.category LIKE :q')
                ->orWhere('bl.resume LIKE :q')
                ->orWhere('bl.keywords LIKE :q')
                ->orWhere('bl.metaDescription LIKE :q')
                ->orWhere('bl.description LIKE :q')
                ->setParameter('q', '%' . $data->q . '%');
        }
        if ($data->category) {
            $query->andWhere('bl.category IN (:category)')
                ->setParameter('category', $data->category);
        }

        if ($data->boutique_id) {
            $query->andWhere('b.id = :boutiqueId')
                ->setParameter('boutiqueId', $data->boutique_id);
        }
        if ($validate == true) {
            $query->andWhere('bl.validate = :validate')
                ->setParameter('validate', true);
        }

        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Blog[] Returns an array of Blog objects
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
    public function findOneBySomeField($value): ?Blog
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
