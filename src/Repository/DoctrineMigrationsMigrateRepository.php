<?php

namespace App\Repository;

use App\Entity\DoctrineMigrationsMigrate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctrineMigrationsMigrate>
 *
 * @method DoctrineMigrationsMigrate|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctrineMigrationsMigrate|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctrineMigrationsMigrate[]    findAll()
 * @method DoctrineMigrationsMigrate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineMigrationsMigrateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineMigrationsMigrate::class);
    }

//    /**
//     * @return DoctrineMigrationsMigrate[] Returns an array of DoctrineMigrationsMigrate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DoctrineMigrationsMigrate
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
