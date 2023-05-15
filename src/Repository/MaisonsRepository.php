<?php

namespace App\Repository;

use App\Entity\Maisons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Maisons>
 *
 * @method Maisons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maisons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maisons[]    findAll()
 * @method Maisons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaisonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maisons::class);
    }

    public function save(Maisons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Maisons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Maisons[] Returns an array of Maisons objects
     */
    public function findWithoutOffset($prix, $superficie, $ville): array
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.biens', 'b')
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
     * @return Maisons[] Returns an array of Maisons objects
     */
    public function findWithOffset($prix, $superficie, $ville, $page): array
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.biens', 'b')
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
//     * @return Maisons[] Returns an array of Maisons objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Maisons
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
