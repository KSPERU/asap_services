<?php

namespace App\Repository;

use App\Entity\Metodocobro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Metodocobro>
 *
 * @method Metodocobro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metodocobro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metodocobro[]    findAll()
 * @method Metodocobro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetodocobroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Metodocobro::class);
    }

//    /**
//     * @return Metodocobro[] Returns an array of Metodocobro objects
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

//    public function findOneBySomeField($value): ?Metodocobro
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
