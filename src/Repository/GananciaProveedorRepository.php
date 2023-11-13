<?php

namespace App\Repository;

use App\Entity\GananciaProveedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GananciaProveedor>
 *
 * @method GananciaProveedor|null find($id, $lockMode = null, $lockVersion = null)
 * @method GananciaProveedor|null findOneBy(array $criteria, array $orderBy = null)
 * @method GananciaProveedor[]    findAll()
 * @method GananciaProveedor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GananciaProveedorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GananciaProveedor::class);
    }

//    /**
//     * @return GananciaProveedor[] Returns an array of GananciaProveedor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GananciaProveedor
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}