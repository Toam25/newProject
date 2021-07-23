<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findConversationsByParticipants(int $otherId, int $myId)
    {
        $qb =  $this->createQueryBuilder('c');
        $qb->select('c', 'p')
            ->innerJoin('c.participants', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.user', ':me'),
                    $qb->expr()->eq('p.user', ':otherUser')
                )
            )
            ->groupBy('p.conversation')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversation'),
                    2
                )
            )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherId
            ]);
        return $qb->getQuery()->getResult();
    }

    public function findConvesationsByUser(int $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('otherUser.name', 'otherUser.firstname', 'otherUser.id', 'b.name as shop_name', 'b.logo as shop_logo', 'b.type as shop_type', 'b.id as shop_id', 'otherUser.avatar', 'c.id as conversationId', 'lm.content', 'lm.createdAt', 'lm.deleteFrom')
            ->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.user', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessage', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->leftJoin('otherUser.boutiques', 'b')
            ->where('meUser.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('lm.createdAt', 'DESC');
        return $qb->getQuery()
            ->getResult();
    }
    public function findConvesationsByUserAndMe(int $userId, int $otheruserId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('otherUser.name', 'otherUser.firstname', 'otherUser.id', 'b.name as shop_name', 'b.logo as shop_logo', 'otherUser.avatar', 'c.id as conversationId', 'lm.id as idMessage', 'lm.content', 'lm.createdAt')
            ->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.user', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessage', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->leftJoin('otherUser.boutiques', 'b')
            ->where('meUser.id = :user')
            ->andWhere('otherUser.id = :otherUser')
            ->setParameters([
                'user' => $userId,
                'otherUser' => $otheruserId
            ])
            ->orderBy('lm.createdAt', 'DESC');
        return $qb->getQuery()->getOneOrNullResult();
    }
    public function checkIfUserIsParticipant(int $conversationId, int $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->innerJoin('c.participants', 'p')
            ->where('c.id = :conversationId')
            ->andWhere($qb->expr()->eq('p.user', ':userId'))
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ]);
        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
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
    public function findOneBySomeField($value): ?Conversation
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
