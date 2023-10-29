<?php

namespace App\Repository;

use App\Entity\Historialservicios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Historialservicios>
 *
 * @method Historialservicios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historialservicios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historialservicios[]    findAll()
 * @method Historialservicios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorialserviciosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historialservicios::class);
    }

//    /**
//     * @return Historialservicios[] Returns an array of Historialservicios objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Historialservicios
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
