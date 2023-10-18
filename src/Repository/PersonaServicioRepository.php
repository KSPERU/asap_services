<?php

namespace App\Repository;

use App\Entity\PersonaServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonaServicio>
 *
 * @method PersonaServicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonaServicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonaServicio[]    findAll()
 * @method PersonaServicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonaServicio::class);
    }

//    /**
//     * @return PersonaServicio[] Returns an array of PersonaServicio objects
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

//    public function findOneBySomeField($value): ?PersonaServicio
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
