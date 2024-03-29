<?php

namespace App\Repository;

use App\Entity\Comptecourant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comptecourant>
 *
 * @method Comptecourant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comptecourant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comptecourant[]    findAll()
 * @method Comptecourant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComptecourantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comptecourant::class);
    }

//    /**
//     * @return Comptecourant[] Returns an array of Comptecourant objects
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

//    public function findOneBySomeField($value): ?Comptecourant
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
