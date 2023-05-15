<?php

namespace App\Repository;

use App\Entity\PhotosBiens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhotosBiens>
 *
 * @method PhotosBiens|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotosBiens|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotosBiens[]    findAll()
 * @method PhotosBiens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotosBiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotosBiens::class);
    }

    public function save(PhotosBiens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PhotosBiens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return PhotosBiens[] Returns an array of PhotosBiens objects
     */
    public function findByBien($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.biens = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return PhotosBiens[] Returns an array of PhotosBiens objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PhotosBiens
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
