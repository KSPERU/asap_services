<?php

namespace App\Repository;

use App\Entity\CuentaNiubiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CuentaNiubiz>
 *
 * @method CuentaNiubiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method CuentaNiubiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method CuentaNiubiz[]    findAll()
 * @method CuentaNiubiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentaNiubizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CuentaNiubiz::class);
    }

//    /**
//     * @return CuentaNiubiz[] Returns an array of CuentaNiubiz objects
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

//    public function findOneBySomeField($value): ?CuentaNiubiz
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
