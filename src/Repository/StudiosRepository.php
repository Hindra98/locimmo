<?php

namespace App\Repository;

use App\Entity\Studios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Studios>
 *
 * @method Studios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studios[]    findAll()
 * @method Studios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudiosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Studios::class);
    }

    public function save(Studios $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Studios $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Studios[] Returns an array of Studios objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Studios[] Returns an array of Studios objects
     */
    public function findWithoutOffset($prix, $superficie, $ville): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.biens', 'b')
            ->andWhere('b.prix <= :prix')
            ->andWhere('b.superficie >= :superficie')
            ->andWhere('b.adresse LIKE :ville')
            ->setParameter('prix', $prix)
            ->setParameter('superficie', $superficie)
            ->setParameter('ville', '%'.$ville.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
     * @return Studios[] Returns an array of Studios objects
     */
    public function findWithOffset($prix, $superficie, $ville, $page): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.biens', 'b')
            ->andWhere('b.prix <= :prix')
            ->andWhere('b.superficie >= :superficie')
            ->andWhere('b.adresse LIKE :ville')
            ->setFirstResult(($page-1)*12)
            ->setMaxResults(12)
            ->setParameter('prix', $prix)
            ->setParameter('superficie', $superficie)
            ->setParameter('ville', '%'.$ville.'%')
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Studios[] Returns an array of Studios objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Studios
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
