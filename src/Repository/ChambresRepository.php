<?php

namespace App\Repository;

use App\Entity\Biens;
use App\Entity\Chambres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chambres>
 *
 * @method Chambres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chambres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chambres[]    findAll()
 * @method Chambres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChambresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambres::class);
    }

    public function save(Chambres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Chambres $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Chambres[] Returns an array of Chambres objects
     */
    public function findWithoutOffset($prix, $superficie, $ville): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.biens', 'b')
            ->where('b.prix <= :prix')
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
     * @return Chambres[] Returns an array of Chambres objects
     */
    public function findWithOffset($prix, $superficie, $ville, $page): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.biens', 'b')
            ->where('b.prix <= :prix')
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
//     * @return Chambres[] Returns an array of Chambres objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chambres
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
