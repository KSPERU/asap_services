<?php

namespace App\Repository;

use App\Entity\MetcobroProveedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MetcobroProveedor>
 *
 * @method MetcobroProveedor|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetcobroProveedor|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetcobroProveedor[]    findAll()
 * @method MetcobroProveedor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetcobroProveedorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MetcobroProveedor::class);
    }

//    /**
//     * @return MetcobroProveedor[] Returns an array of MetcobroProveedor objects
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

//    public function findOneBySomeField($value): ?MetcobroProveedor
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
