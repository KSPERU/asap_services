<?php

namespace App\Repository;

use App\Entity\Promocion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promocion>
 *
 * @method Promocion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promocion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promocion[]    findAll()
 * @method Promocion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromocionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promocion::class);
    }

//    /**
//     * @return Promocion[] Returns an array of Promocion objects
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

//    public function findOneBySomeField($value): ?Promocion
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
