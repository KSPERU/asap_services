<?php

namespace App\Repository;

use App\Entity\Conversacion;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversacion>
 *
 * @method Conversacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversacion[]    findAll()
 * @method Conversacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversacion::class);
    }

//    /**
//     * @return Conversacion[] Returns an array of Conversacion objects
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

//    public function findOneBySomeField($value): ?Conversacion
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findConversationsByUser(int $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
            select('otroUsuario.email', 'c.id as conversationId', 'lm.mensaje', 'lm.fecha_creacion')
            ->innerJoin('c.participantes', 'p', Join::WITH, $qb->expr()->neq('p.usuario_id', ':user'))
            ->innerJoin('c.participantes', 'me', Join::WITH, $qb->expr()->eq('me.usuario_id', ':user'))
            ->leftJoin('c.ultimo_mensaje_id', 'lm')
            ->innerJoin('me.usuario_id', 'meUser')
            ->innerJoin('p.usuario_id', 'otroUsuario')
            ->where('meUser.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('lm.fecha_creacion', 'DESC')
        ;
        
        return $qb->getQuery()->getResult();
    }

    public function findConversationByParticipants(int $otherUserId, int $myId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select($qb->expr()->count('p.conversacion_id'))
            ->innerJoin('c.participantes', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.usuario_id', ':me'),
                    $qb->expr()->eq('p.usuario_id', ':otherUser')
                )
            )
            ->groupBy('p.conversacion_id')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversacion_id'),
                    2
                )
            )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherUserId
            ])
        ;

        return $qb->getQuery()->getResult();
    }
}
