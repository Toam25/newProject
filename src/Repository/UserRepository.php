<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findAllWithRoleSuperAdmin(String $role)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%' . $role . '%')
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function findAllUser(string $q = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u', 's', 'h')
            ->leftJoin('u.boutiques', 's')
            ->leftJoin('s.headers', 'h')
            ->orderBy('u.id', 'ASC');
        if ($q != null) {
            $qb->Where('s.name LIKE :shopname')
                ->orWhere('u.name LIKE :name')
                ->orWhere('u.firstname LIKE :firstname')
                ->setParameters([
                    'shopname' => '%' . $q . '%',
                    'name' => '%' . $q . '%',
                    'firstname' => '%' . $q . '%'
                ]);
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findOneByUser(int $userId)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u', 's', 'h')
            ->leftJoin('u.boutiques', 's')
            ->leftJoin('s.headers', 'h')
            ->orderBy('u.id', 'ASC')
            ->andWhere('u.id = :userId ')
            ->setParameters([
                'userId' => $userId
            ]);

        return $qb->getQuery()->getOneOrNullResult();
    }
    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
