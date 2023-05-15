<?php

namespace App\Repository;

use App\Entity\Appartements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appartements>
 *
 * @method Appartements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartements[]    findAll()
 * @method Appartements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appartements::class);
    }

    public function save(Appartements $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appartements $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Appartements[] Returns an array of Appartements objects
     */
    public function findWithoutOffset($prix, $superficie, $ville): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.biens', 'b')
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
     * @return Appartements[] Returns an array of Appartements objects
     */
    public function findWithOffset($prix, $superficie, $ville, $page): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.biens', 'b')
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
//     * @return Appartements[] Returns an array of Appartements objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Appartements
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
