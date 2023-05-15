<?php

namespace App\Repository;

use App\Entity\Messages;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Messages>
 *
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    public function save(Messages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Messages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function findUsers($user): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.utilisateur_send = :user OR m.utilisateur_receive = :user')
            ->setParameter('user', $user)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function findMessUnread($user): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.lu = 0 AND m.utilisateur_receive = :user')
            ->setParameter('user', $user)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function countMessUnread($user, $receive): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.lu = 0 AND m.utilisateur_receive = :user AND m.utilisateur_send = :receive')
            ->setParameter('user', $user)
            ->setParameter('receive', $receive)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Messages[] Returns an array of Messages objects
     */
    public function findMessage($user, $receive): array
    {
        return $this->createQueryBuilder('m')
            ->where('(m.utilisateur_receive = :user AND m.utilisateur_send = :receive) OR (m.utilisateur_send = :user AND m.utilisateur_receive = :receive)')
            ->setParameter('user', $user)
            ->setParameter('receive', $receive)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Messages
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
