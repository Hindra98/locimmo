<?php

namespace App\Repository;

use App\Entity\Biens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use function PHPUnit\Framework\isNull;

/**
 * @extends ServiceEntityRepository<Biens>
 *
 * @method Biens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biens[]    findAll()
 * @method Biens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biens::class);
    }

    public function save(Biens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Biens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUserWithOfset($user, $page): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.proprietaires = :user')
            ->setFirstResult(($page-1)*10)
            ->setMaxResults(10)
            ->setParameter('user', $user)
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Biens[] Returns an array of Biens objects
     */
    public function findWithoutOffset($prix, $superficie, $ville): array
    {
        return $this->createQueryBuilder('b')
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
     * @return Biens[] Returns an array of Biens objects
     */
    public function findWithOffset($prix, $superficie, $ville, $page): array
    {
        return $this->createQueryBuilder('b')
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

    public function findByIndex(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByUser($proprio): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.proprietaires = :proprio')
            ->setParameter('proprio', $proprio)
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Biens[] Returns an array of Biens objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Biens
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
